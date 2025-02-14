<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <!-- CRM Section -->
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#crm" aria-expanded="false" aria-controls="crm">
                <i class="menu-icon mdi mdi-account-multiple"></i>
                <span class="menu-title">CRM</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="crm">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('crm.customers.index') }}">Customers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('crm.companies.index') }}">Companies</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Tasks Section -->
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#tasks" aria-expanded="false" aria-controls="tasks">
                <i class="menu-icon mdi mdi-chart-line"></i>
                <span class="menu-title">Tasks</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="tasks">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('crm.tasks.index') }}">Tasks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('crm.task-templates.index') }}">Templates</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Documents Section -->
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#documents" aria-expanded="false" aria-controls="documents">
                <i class="menu-icon mdi mdi-folder-multiple"></i>
                <span class="menu-title">Documents</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="documents">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('documents.index') }}">All Documents</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('folders.index') }}">Folder Management</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Finances Section -->
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#finances" aria-expanded="false" aria-controls="finances">
                <i class="menu-icon mdi mdi-table"></i>
                <span class="menu-title">Finances</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="finances">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('finance.accounts.index') }}">Accounts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('finance.transactions.index') }}">Transactions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('finance.invoices.index') }}">Invoices</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('finance.expenses.index') }}">Expenses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('finance.reports.dashboard') }}">Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('finance.budgets.index') }}">Budget</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('finance.bank-transactions.index') }}">Bank Transactions</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Inventory Section -->
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#inventory" aria-expanded="false" aria-controls="inventory">
                <i class="menu-icon mdi mdi-layers-outline"></i>
                <span class="menu-title">Inventory</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="inventory">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('inventory.index') }}">Inventory Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('purchase_orders.index') }}">Purchase Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('suppliers.index') }}">Suppliers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('warehouses.index') }}">Warehouses</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('inventory.reports') }}">Reports</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('inventory.valuation') }}">Valuations</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Other Sections -->
        <!-- (Add additional sections as needed.) -->

    </ul>
</nav>
