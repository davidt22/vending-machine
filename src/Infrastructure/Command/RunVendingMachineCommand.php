<?php

namespace App\Infrastructure\Command;

use App\Application\Product\BuyProduct\BuyProductRequest;
use App\Application\Product\BuyProduct\BuyProductService;
use App\Application\Product\FindProducts\FindProductsRequest;
use App\Application\Product\FindProducts\FindProductsService;
use App\Application\VendingMachine\CreateVendingMachine\CreateVendingMachineRequest;
use App\Application\VendingMachine\CreateVendingMachine\CreateVendingMachineService;
use App\Application\VendingMachine\InsertCoins\InsertCoinsRequest;
use App\Application\VendingMachine\InsertCoins\InsertCoinsService;
use App\Application\VendingMachine\ReturnCoins\ReturnCoinsRequest;
use App\Application\VendingMachine\ReturnCoins\ReturnCoinsService;
use App\Domain\Model\Coin\Coin;
use App\Domain\Model\Coin\CoinId;
use App\Domain\Model\Coin\CoinValue;
use App\Domain\Model\Product\Product;
use App\Domain\Model\Product\ProductId;
use App\Domain\Model\Product\ProductName;
use App\Domain\Model\Product\ProductPrice;
use App\Domain\Model\Product\ProductQuantity;
use App\Domain\Model\VendingMachine\NotEnoughCoinsException;
use App\Domain\Model\VendingMachine\VendingMachine;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunVendingMachineCommand extends Command
{
    const VENDING_MACHINE = 'Vending Machine';
    protected static $defaultName = 'app:vending:run';

    private VendingMachine $vendingMachine;
    private CreateVendingMachineService $createVendingMachineService;
    private FindProductsService $findProductsService;
    private BuyProductService $buyProductService;
    private InsertCoinsService $insertCoinsService;
    private ReturnCoinsService $returnCoinsService;

    public function __construct(
        CreateVendingMachineService $createVendingMachineService,
        FindProductsService $findProductsService,
        BuyProductService $buyProductService,
        InsertCoinsService $insertCoinsService,
        ReturnCoinsService $returnCoinsService
    ) {
        parent::__construct();

        $this->createVendingMachineService = $createVendingMachineService;
        $this->findProductsService = $findProductsService;
        $this->buyProductService = $buyProductService;
        $this->insertCoinsService = $insertCoinsService;
        $this->returnCoinsService = $returnCoinsService;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bootstrap();

        $this->buyWaterWithoutExactChange($output);
        $this->addCoinsButReturnCoins($output);
        $this->buySodaWithExactChange($output);
//
        $this->showMachineResume($output);

        return Command::SUCCESS;
    }

    private function bootstrap()
    {
        $coins = $this->registerCoins();
        $this->createMachine($coins);
        $this->addProductsToMachine();
    }

    private function registerCoins(): array
    {
        return [
            Coin::create(
                new CoinId(),
                CoinValue::fromValue(0.05)
            ),
            Coin::create(
                new CoinId(),
                CoinValue::fromValue(0.10)
            ),
            Coin::create(
                new CoinId(),
                CoinValue::fromValue(0.25)
            ),
            Coin::create(
                new CoinId(),
                CoinValue::fromValue(1)
            ),
        ];
    }

    private function createMachine(array $coins): void
    {
        $createVendingMachineRequest = new CreateVendingMachineRequest(self::VENDING_MACHINE, $coins);
        $this->vendingMachine = $this->createVendingMachineService->execute($createVendingMachineRequest);
    }

    private function addProductsToMachine(): void
    {
        $this->vendingMachine->addProduct(
            Product::create(
                new ProductId(),
                ProductName::fromValue(ProductName::WATER),
                ProductPrice::fromValue(0.65),
                ProductQuantity::fromValue(10),
                $this->vendingMachine
            )
        );

        $this->vendingMachine->addProduct(
            Product::create(
                new ProductId(),
                ProductName::fromValue(ProductName::JUICE),
                ProductPrice::fromValue(1.00),
                ProductQuantity::fromValue(10),
                $this->vendingMachine
            )
        );

        $this->vendingMachine->addProduct(
            Product::create(
                new ProductId(),
                ProductName::fromValue(ProductName::SODA),
                ProductPrice::fromValue(1.50),
                ProductQuantity::fromValue(10),
                $this->vendingMachine
            )
        );
    }

    /**
     * @throws NotEnoughCoinsException
     */
    private function buySodaWithExactChange(OutputInterface $output): void
    {
        $resultSoda = $this->buyProductService->execute(new BuyProductRequest(ProductName::SODA, [
            Coin::create(
                new CoinId(),
                CoinValue::fromValue(CoinValue::ONE_EUR)
            ),
            Coin::create(
                new CoinId(),
                CoinValue::fromValue(CoinValue::TWEENTY_FIVE_CENTS)
            ),
            Coin::create(
                new CoinId(),
                CoinValue::fromValue(CoinValue::TWEENTY_FIVE_CENTS)
            ),
        ], self::VENDING_MACHINE));

        $output->writeln('Buy Soda with exact change');
        $output->writeln($resultSoda['product']);
        foreach ($resultSoda['change'] as $value) {
            $output->writeln($value);
        }
        $output->writeln('-----');
    }

    private function addCoinsButReturnCoins(OutputInterface $output): void
    {
        $this->insertCoinsService->execute(new InsertCoinsRequest(
                self::VENDING_MACHINE, [
                Coin::create(
                    new CoinId(),
                    CoinValue::fromValue(CoinValue::TEN_CENTS)
                ),
                Coin::create(
                    new CoinId(),
                    CoinValue::fromValue(CoinValue::TEN_CENTS)
                ),
            ])
        );
        $resultReturnCoins = $this->returnCoinsService->execute(new ReturnCoinsRequest(self::VENDING_MACHINE));

        $output->writeln('Start adding coins, but user ask for return coin');
        foreach ($resultReturnCoins as $returnCoin) {
            $output->writeln($returnCoin);
        }
        $output->writeln('-----');
    }

    private function showMachineResume(OutputInterface $output): void
    {
        $output->writeln('Vending Machine Final Status');
        $products = $this->findProductsService->execute(new FindProductsRequest(self::VENDING_MACHINE));
        $output->writeln('Products:');
        /** @var Product $product */
        foreach ($products as $product) {
            $output->writeln($product);
        }
    }

    /**
     * @throws NotEnoughCoinsException
     */
    private function buyWaterWithoutExactChange(OutputInterface $output): void
    {
        $request = new BuyProductRequest(ProductName::WATER, [
            Coin::create(
                new CoinId(),
                CoinValue::fromValue(CoinValue::ONE_EUR)
            )
        ], self::VENDING_MACHINE);

        $result = $this->buyProductService->execute($request);

        $output->writeln('Buy water without exact change');
        $output->writeln($result['product']);
        foreach ($result['change'] as $value) {
            $output->writeln($value);
        }
        $output->writeln('-----');
    }
}