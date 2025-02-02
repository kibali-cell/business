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
            <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
                <i class="menu-icon mdi mdi-chart-line"></i>
                <span class="menu-title">Tasks</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="charts">
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
            <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                <i class="menu-icon mdi mdi-table"></i>
                <span class="menu-title">Finances</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="tables">
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
                </ul>
            </div>
        </li>

        <!-- Other Sections (Icons, User Pages, Documentation) -->
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
                <i class="menu-icon mdi mdi-layers-outline"></i>
                <span class="menu-title">Icons</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="icons">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="pages/icons/font-awesome.html">Font Awesome</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <i class="menu-icon mdi mdi-account-circle-outline"></i>
                <span class="menu-title">User Pages</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="home/pages/samples/blank-page.html">Blank Page</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="home/pages/samples/error-404.html">404</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="home/pages/samples/error-500.html">500</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="home/pages/samples/login.html">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="home/pages/samples/register.html">Register</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="docs/documentation.html">
                <i class="menu-icon mdi mdi-file-document"></i>
                <span class="menu-title">Documentation</span>
            </a>
        </li>
    </ul>
</nav>