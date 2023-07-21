<?php

namespace App\Console\Commands;

use App\Contracts\ProductServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

class NewProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'new:product {--name=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add new products';

    /**
     * Execute the console command.
     */
    public function handle(ProductServiceInterface $service)
    {
        try {
            $service->storeCommand(['name' => $this->option('name')]);
            $this->info('Product added successfully');
        } catch (ValidationException $e) {
            $this->error($e->getMessage());
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

    }
}
