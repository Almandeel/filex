<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Group;
use App\Account;
class GroupsAccountsSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return  void
    */
    public function run()
    {
        $this->command->info('... Truncating Groups, Accounts Tables ...');
        $this->truncateTables();
        
        $this->command->info('... Creating Group ...');
        $assetsGroup = Group::firstOrCreate(['name' => 'assets']);
        $this->command->info('Creating Assets Group ...');
        
        $liabilitiesGroup = Group::firstOrCreate(['name' => 'liabilities']);
        $this->command->info('Creating Liabilities Group ...');
        
        $ownersGroup = Group::firstOrCreate(['name' => 'owners']);
        $this->command->info('Creating Owners Group ...');
        
        $revenuesGroup = Group::firstOrCreate(['name' => 'revenues']);
        $this->command->info('Creating Revenues Group ...');
        
        $expensesGroup = Group::firstOrCreate(['name' => 'expenses']);
        $this->command->info('Creating Expenses Group ...');
        
        $currentAssetsGroup = Group::firstOrCreate(['name' => 'currentAssets', 'group_id' => $assetsGroup->id]);
        $this->command->info('Creating Curent Assets Group ...');
        
        $storesGroup = Group::firstOrCreate(['name' => 'stores', 'group_id' => $currentAssetsGroup->id]);
        $this->command->info('Creating Stores Group ...');
        
        $customersGroup = Group::firstOrCreate(['name' => 'customers', 'group_id' => $currentAssetsGroup->id]);
        $this->command->info('Creating Customers Group ...');

        $cashiersGroup = Group::firstOrCreate(['name' => 'cashiers', 'group_id' => $currentAssetsGroup->id]);
        $this->command->info('Creating cashiers Group ...');
        
        $collaboratorsGroup = Group::firstOrCreate(['name' => 'collaborators', 'group_id' => $currentAssetsGroup->id]);
        $this->command->info('Creating Collaborators Group ...');
        
        $safesGroup = Group::firstOrCreate(['name' => 'safes', 'group_id' => $currentAssetsGroup->id]);
        $this->command->info('Creating Safes Group ...');
        
        $suppliersGroup = Group::firstOrCreate(['name' => 'suppliers', 'group_id' => $liabilitiesGroup->id]);
        $this->command->info('Creating Suppliers Group ...');
        
        $this->command->info('... Creating Accounts ...');
        $safeAccount = Account::firstOrCreate(['name' => 'safe', 'group_id' => $currentAssetsGroup->id]);
        $this->command->info('Creating Safe Account...');
        
        $bankAccount = Account::firstOrCreate(['name' => 'bank', 'group_id' => $currentAssetsGroup->id]);
        $this->command->info('Creating Bank Account...');
        
        $debtsAccount = Account::firstOrCreate(['name' => 'debts', 'group_id' => $currentAssetsGroup->id]);
        $this->command->info('Creating Debts Account...');
        
        $creditsAccount = Account::firstOrCreate(['name' => 'credits', 'group_id' => $currentAssetsGroup->id]);
        $this->command->info('Creating Credits Account...');
        
        $creditsAccount = Account::firstOrCreate(['name' => 'expenses', 'group_id' => $expensesGroup->id]);
        $this->command->info('Creating Expenses Account...');
        
        $creditsAccount = Account::firstOrCreate(['name' => 'credits', 'group_id' => $currentAssetsGroup->id]);
        $this->command->info('Creating Credits Account...');
        
        $capitalAccount = Account::firstOrCreate(['name' => 'capital', 'group_id' => $ownersGroup->id]);
        
        $revenuesAccount = Account::firstOrCreate(['name' => 'revenues', 'group_id' => $revenuesGroup->id]);
        $salesAccount = Account::firstOrCreate(['name' => 'sales', 'group_id' => $revenuesGroup->id]);
        $salesReturnsAccount = Account::firstOrCreate(['name' => 'salesReturns', 'group_id' => $revenuesGroup->id]);
        
        $expensesAccount = Account::firstOrCreate(['name' => 'expenses', 'group_id' => $expensesGroup->id]);
        $purchasesAccount = Account::firstOrCreate(['name' => 'purchases', 'group_id' => $expensesGroup->id]);
        $purchasesChargesAccount = Account::firstOrCreate(['name' => 'purchasesCharges', 'group_id' => $expensesGroup->id]);
        $purchasesReturnsAccount = Account::firstOrCreate(['name' => 'purchasesReturns', 'group_id' => $expensesGroup->id]);
        
    }
    
    /**
    * Truncates all the laratrust tables and the users table
    *
    * @return    void
    */
    public function truncateTables()
    {
        // Schema::disableForeignKeyConstraints();
        // \App\Group::truncate();
        // \App\Account::truncate();
        // Schema::enableForeignKeyConstraints();
    }
}