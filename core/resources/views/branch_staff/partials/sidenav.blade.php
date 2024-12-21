<div class="sidebar bg--dark">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a class="sidebar__main-logo" href="{{ route('staff.dashboard') }}">
                <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="@lang('image')">
            </a>
        </div>
        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{ menuActive('staff.dashboard') }}">
                    <a class="nav-link" href="{{ route('staff.dashboard') }}">
                        <i class="menu-icon la la-home"></i>
                        <span class="menu-title">@lang('Home')</span>
                    </a>
                </li>

                @if (authStaff()->designation == Status::ROLE_CUSTOMER_SERVICE && $general->modules->branch_create_user)
                
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive('staff.account.open*', 3) }}">
                        <i class="menu-icon las la-user-plus"></i>
                        <span class="menu-title">@lang('Open Account') </span>
                    </a>

                    <div class="sidebar-submenu {{ menuActive('staff.account.open*', 2) }} ">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('staff.account.open.individual') }}">
                                <a href="{{ route('staff.account.open.individual') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Individual Account')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('staff.account.open.joint') }}">
                                <a href="{{ route('staff.account.open.joint') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Joint Account')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('staff.account.open.corporate') }}">
                                <a href="{{ route('staff.account.open.corporate') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Corporate Account')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                
                @endif
                
                <li class="sidebar-menu-item {{ menuActive(['staff.customer.customers.all', 'staff.customer.detail']) }}">
                    <a class="nav-link" href="{{ route('staff.customer.customers.all') }}">
                        <i class="menu-icon las la-users"></i>
                        <span class="menu-title">@lang('Customers')</span>
                    </a>
                </li>
                
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive(['staff.account.accounts*', 'staff.account.detail.*'], 3) }}">
                        <i class="menu-icon las la-suitcase"></i>
                        <span class="menu-title">@lang('View Accounts')</span>
                        
                        @if (isManager())
                            <span class="menu-badge pill bg--danger ms-auto">
                                <i class="fa fa-exclamation"></i>
                            </span>
                        @endif
                    </a>

                    <div class="sidebar-submenu {{ menuActive(['staff.account.accounts*', 'staff.account.detail.*'], 2) }} ">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive(['staff.account.accounts.individual', 'staff.account.detail.individual']) }}">
                                <a href="{{ route('staff.account.accounts.individual') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Individual Accounts')</span>
                                    @if (isManager())
                                        <span class="menu-badge pill bg--danger ms-auto">{{ 1 }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.account.accounts.joint', 'staff.account.detail.joint']) }}">
                                <a href="{{ route('staff.account.accounts.joint') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Joint Accounts')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.account.accounts.corporate', 'staff.account.detail.corporate']) }}">
                                <a href="{{ route('staff.account.accounts.corporate') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Corporate Accounts')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                
                @if(authStaff()->designation == 10)

                <li class="sidebar-menu-item {{ menuActive('staff.deposits') }}">
                    <a class="nav-link" href="{{ route('staff.deposits') }}">
                        <i class="menu-icon las la-wallet"></i>
                        <span class="menu-title">@lang('Deposits')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ menuActive('staff.withdrawals') }}">
                    <a class="nav-link" href="{{ route('staff.withdrawals') }}">
                        <i class="menu-icon las la-hand-holding-usd"></i>
                        <span class="menu-title">@lang('Withdrawals')</span>
                    </a>
                </li>
                
                @endif
                
                @if(authStaff()->designation != Status::ROLE_CUSTOMER_SERVICE)

                <li class="sidebar-menu-item {{ menuActive(['staff.transactions.index', 'staff.transactions.detail']) }}">
                    <a class="nav-link" href="{{ route('staff.transactions.index') }}">
                        <i class="menu-icon las la-exchange-alt"></i>
                        <span class="menu-title">@lang('Transactions')</span>
                    </a>
                </li>
                @endif

                @if (isManager())
                
                <li class="sidebar-menu-item {{ menuActive('staff.branches') }}">
                    <a class="nav-link" href="{{ route('staff.branches') }}">
                        <i class="menu-icon las la-project-diagram"></i>
                        <span class="menu-title">@lang('My Branches')</span>
                    </a>
                </li>
                    
                @endif
                
                @if (authStaff()->designation == Status::ROLE_ACCOUNTING || authStaff()->designation == Status::ROLE_CUSTOMER_SERVICE || isManager())
                
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive(['staff.murabaha.*'], 3) }}">
                        <i class="menu-icon las la-coins"></i>
                        <span class="menu-title">@lang('Murabaha')</span>
                    </a>

                    <div class="sidebar-submenu {{ menuActive(['staff.murabaha.*'], 2) }} ">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive(['staff.murabaha.applications', 'staff.murabaha.new.application',
                            'staff.murabaha.application.*']) }}">
                                <a href="{{ route('staff.murabaha.applications') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Applications')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.murabaha.guarantors', 'staff.murabaha.new.guarantor', 'staff.murabaha.guarantor.detail']) }}">
                                <a href="{{ route('staff.murabaha.guarantors') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Guarantors')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.murabaha.products']) }}">
                                <a href="{{ route('staff.murabaha.products') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Products')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.murabaha.suppliers']) }}">
                                <a href="{{ route('staff.murabaha.suppliers') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Suppliers')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.murabaha.purchases']) }}">
                                <a href="{{ route('staff.murabaha.purchases') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Purchases')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.murabaha.products']) }}">
                                <a href="{{ route('staff.murabaha.products') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Inventory')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                
                @endif
                
                @if (authStaff()->designation == Status::ROLE_ACCOUNTING)
                
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive(['staff.coa.*', 'staff.journal.*', 'staff.teller.*'], 3) }}">
                        <i class="menu-icon las la-calculator"></i>
                        <span class="menu-title">@lang('Accounting')</span>
                    </a>

                    <div class="sidebar-submenu {{ menuActive(['staff.coa.*', 'staff.journal.*', 'staff.teller.*'], 2) }} ">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('staff.coa.index') }}">
                                <a href="{{ route('staff.coa.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Chart of Accounts')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.journal.index', 'staff.journal.detail']) }}">
                                <a href="{{ route('staff.journal.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Journal')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.teller.index']) }}">
                                <a href="{{ route('staff.teller.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Teller Funds')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                
                @endif
                
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive(['staff.coa.*', 'staff.journal.*', 'staff.teller.*'], 3) }}">
                        <i class="menu-icon las la-calculator"></i>
                        <span class="menu-title">@lang('Reports')</span>
                    </a>

                    <div class="sidebar-submenu {{ menuActive(['staff.report.*'], 2) }} ">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('staff.report.index') }}">
                                <a href="{{ route('staff.report.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Account Statement')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.journal.index', 'staff.journal.detail']) }}">
                                <a href="{{ route('staff.journal.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Deposits')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.teller.index']) }}">
                                <a href="{{ route('staff.teller.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Withdrawals')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.teller.index']) }}">
                                <a href="{{ route('staff.teller.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Commission')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.teller.index']) }}">
                                <a href="{{ route('staff.teller.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Murabaha Financing')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.teller.index']) }}">
                                <a href="{{ route('staff.teller.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Accounts Receivable')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.teller.index']) }}">
                                <a href="{{ route('staff.teller.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Accounts Payable')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.teller.index']) }}">
                                <a href="{{ route('staff.teller.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Owner Equity')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.teller.index']) }}">
                                <a href="{{ route('staff.teller.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Expenses')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.teller.index']) }}">
                                <a href="{{ route('staff.teller.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Sales')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.teller.index']) }}">
                                <a href="{{ route('staff.teller.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Cash Flow')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['staff.teller.index']) }}">
                                <a href="{{ route('staff.teller.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Teller Transactions')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</div>
<!-- sidebar end -->

@push('script')
    <script>
        if ($('li').hasClass('active')) {
            $('#sidebar__menuWrapper').animate({
                scrollTop: eval($(".active").offset().top - 320)
            }, 500);
        }
    </script>
@endpush
