<aside class="main-sidebar">

    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('dashboard/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->username }}</p>
            </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
            @permission('stores-read')
                    <li class="treeview {{ (request()->segment(1) == 'stores' || request()->segment(1) == 'transferstores') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-home"></i> <span>الادارة</span>
                            <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                        </a>
                        <ul class="treeview-menu" style="display: none;">

                            <li class="{{ (request()->segment(1) == '') ? 'active' : '' }}"><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i><span>  لوحة التحكم </span></a></li>
                
                            @permission('stores-read')
                                <li class="treeview {{ (request()->segment(1) == 'stores' || request()->segment(1) == 'transferstores') ? 'active' : '' }}">
                                    <a href="#">
                                        <i class="fa fa-home"></i> <span>المخازن</span>
                                        <span class="pull-right-container">
                                                <i class="fa fa-angle-left pull-right"></i>
                                            </span>
                                    </a>
                                    <ul class="treeview-menu" style="display: none;">

                                        @permission('stores-read')
                                        <li><a href="{{ route('stores.index') }}">
                                                <i class="fa fa-angle-double-left"></i>
                                                <span>قائمة المخازن</span>
                                            </a></li>
                                        @endpermission

                                        @permission('stores-create')
                                        <li><a href="{{ route('transferstores.create') }}">
                                                <i class="fa fa-angle-double-left"></i>
                                                <span>إضافة عملية تحويل</span>
                                            </a></li>
                                        @endpermission

                                        @permission('stores-read')
                                            <li><a href="{{ route('transferstores.index') }}">
                                                <i class="fa fa-angle-double-left"></i>
                                                <span>قائمة التحويلات</span>
                                            </a></li>
                                        @endpermission

                                    </ul>
                                </li>
                            @endpermission

                            @permission(['items-read', 'categories-read' ,'units-read' ])
                                <li class="treeview {{ in_array(request()->segment(1) , ['items-read', 'categories-read' ,'units-read' ]) ? 'active' : '' }}">
                                    <a href="#">
                                        <i class="fa fa-list"></i> <span>المنتجات</span>
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu" style="display: none;">
                                        @permission('items-read')
                                            <li class="{{ (request()->segment(1) == 'items') ? 'active' : '' }}"><a href="{{ route('items.index') }}"><i class="fa fa-cubes"></i><span>  المنتجات </span></a></li>
                                        @endpermission

                                        @permission('categories-read')
                                            <li class="{{ (request()->segment(1) == 'categories') ? 'active' : '' }}"><a href="{{ route('categories.index') }}"><i class="fa fa-book"></i><span>  الاقسام </span></a></li>
                                        @endpermission

                                        @permission('units-read')
                                            <li class="{{ (request()->segment(1) == 'units') ? 'active' : '' }}"><a href="{{ route('units.index') }}"><i class="fa fa-cubes"></i><span>  الوحدات </span></a></li>
                                        @endpermission
                                    </ul>
                                </li>
                            @endpermission

                            @permission('bills-read')
                                <li class="treeview {{ (request()->segment(1) == 'bills') ? 'active' : '' }}">
                                    <a href="#">
                                        <i class="fa fa-list"></i> <span>المشتريات</span>
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu" style="display: none;">

                                        @permission('bills-create')
                                            <li><a href="{{ route('bills.create') }}">
                                                <i class="fa fa-angle-double-left"></i>
                                                <span>إضافة عملية شراء</span>
                                            </a></li>
                                        @endpermission

                                        @permission('bills-read')
                                            <li><a href="{{ route('bills.index') }}">
                                                <i class="fa fa-angle-double-left"></i>
                                                <span>قائمة المشتريات</span>
                                            </a></li>

                                            <li><a href="{{ route('bills.index', ['is_delivered' => 0]) }}">
                                                <i class="fa fa-angle-double-left"></i>
                                                <span>مشتريات غير مستلمة</span>
                                            </a></li>

                                            <li><a href="{{ route('bills.index', ['is_payed' => 0]) }}">
                                                <i class="fa fa-angle-double-left"></i>
                                                <span>مشتريات غير مدفوعة</span>
                                            </a></li>
                                        @endpermission

                                    </ul>
                                </li>
                            @endpermission

                            @permission('invoices-read')
                                <li class="treeview {{ (request()->segment(1) == 'invoices') ? 'active' : '' }}">
                                    <a href="#">
                                        <i class="fa fa-list"></i> <span>المبيعات</span>
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu" style="display: none;">

                                        @permission('invoices-create')
                                            <li><a href="{{ route('invoices.create') }}">
                                                <i class="fa fa-angle-double-left"></i>
                                                <span>إضافة عملية بيع</span>
                                            </a></li>
                                        @endpermission

                                        @permission('invoices-read')
                                            <li><a href="{{ route('invoices.index') }}">
                                                <i class="fa fa-angle-double-left"></i>
                                                <span>قائمة المبيعات</span>
                                            </a></li>

                                            <li><a href="{{ route('invoices.index', ['is_delivered' => 0]) }}">
                                                <i class="fa fa-angle-double-left"></i>
                                                <span>مبيعات غير مسلمة</span>
                                            </a></li>

                                            <li><a href="{{ route('invoices.index', ['is_payed' => 0]) }}">
                                                <i class="fa fa-angle-double-left"></i>
                                                <span>مبيعات غير مدفوعة</span>
                                            </a></li>
                                        @endpermission
                                    </ul>
                                </li>
                            @endpermission

                            @permission(['safes-read','cheques-read', 'expenses-read','accounts-read'])
                                <li class="treeview {{ in_array(request()->segment(1) , ['safes','cheques', 'expenses' ]) ? 'active' : '' }}">
                                    <a href="#">
                                        <i class="fa fa-list"></i> <span>المحاسبة</span>
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu" style="display: none;">
                                        

                                        @permission('safes-read')
                                            <li class="{{ (request()->segment(1) == 'safes') ? 'active' : '' }}">
                                                <a href="{{ route('safes.index') }}">
                                                    <i class="fa fa-money"></i> <span>الخزن</span>
                                                </a>
                                            </li>
                                        @endpermission

                                        @permission('cheques-read')
                                            <li class="{{ (request()->segment(1) == 'cheques') ? 'active' : '' }}"><a href="{{ route('cheques.index') }}"><i class="fa fa-file"></i><span>الشيكات</span></a></li>
                                        @endpermission

                                        @permission('expenses-read')
                                            <li class="{{ (request()->segment(1) == 'expenses') ? 'active' : '' }}"><a href="{{ route('expenses.index') }}"><i class="fa fa-dollar"></i><span>  المنصرفات </span></a></li>
                                        @endpermission

                                    </ul>
                                </li>
                            @endpermission

                            @permission(['suppliers-read', 'collaborator-read', 'employees-read', 'users-read', 'collaborators-read', 'customers-read'])
                                <li class="treeview {{ in_array(request()->segment(1) ,['suppliers', 'collaborator', 'employees', 'users', 'collaborators', 'customers']) ? 'active' : '' }}">
                                    <a href="#">
                                        <i class="fa fa-users"></i> <span>الافراد</span>
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu" style="display: none;">
                                        @permission('customers-read')
                                            <li class="{{ (request()->segment(1) == 'customers') ? 'active' : '' }}"><a href="{{ route('customers.index') }}"><i class="fa fa-angle-double-left"></i><span>  العملاء </span></a></li>
                                        @endpermission
                                        @permission('collaborators-read')
                                            <li class="{{ (request()->segment(1) == 'collaborators') ? 'active' : '' }}"><a href="{{ route('collaborators.index') }}"><i class="fa fa-angle-double-left"></i><span>  المتعاونون </span></a></li>
                                        @endpermission
                                        @permission('suppliers-read')
                                            <li class="{{ (request()->segment(1) == 'suppliers') ? 'active' : '' }}"><a href="{{ route('suppliers.index') }}"><i class="fa fa-angle-double-left"></i><span>  الموردين </span></a></li>
                                        @endpermission

                                        @permission('collaborator-read')
                                            <li class="{{ (request()->segment(1) == 'collaborator') ? 'active' : '' }}"><a href="{{ route('collaborator.index') }}"><i class="fa fa-angle-double-left"></i><span>  العملاء </span></a></li>
                                        @endpermission

                                        @permission('employees-read')
                                            <li class="{{ (request()->segment(1) == 'employees') ? 'active' : '' }}"><a href="{{ route('employees.index') }}"><i class="fa fa-angle-double-left"></i><span>  الموظفين </span></a></li>
                                        @endpermission

                                        @permission('users-read')
                                            <li class="{{ (request()->segment(1) == 'users') ? 'active' : '' }}"><a href="{{ route('users.index') }}"><i class="fa fa-angle-double-left"></i><span>  المستخدمين </span></a></li>
                                        @endpermission
                                    </ul>
                                </li>
                            @endpermission

                        </ul>
                    </li>
            @endpermission 

            {{-- @permission('trips-read') --}}
                    <li class="treeview {{ (request()->segment(1) == 'stores' || request()->segment(1) == 'transferstores') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-home"></i> <span>الرحلات</span>
                            <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                        </a>
                        <ul class="treeview-menu" style="display: none;">

                            @permission('drivers-read')
                                <li class="{{ (request()->segment(1) == '') ? 'active' : '' }}"><a href="{{ route('drivers.index') }}"><i class="fa fa-dashboard"></i><span>  السائقين </span></a></li>
                            @endpermission

                            @permission('cars-read')
                                <li class="{{ (request()->segment(1) == '') ? 'active' : '' }}"><a href="{{ route('cars.index') }}"><i class="fa fa-dashboard"></i><span>  المركبات </span></a></li>
                            @endpermission

                            @permission('states-read')
                                <li class="{{ (request()->segment(1) == '') ? 'active' : '' }}"><a href="{{ route('states.index') }}"><i class="fa fa-dashboard"></i><span>  المدن </span></a></li>
                            @endpermission

                            @permission('trips-read')
                                <li class="{{ (request()->segment(1) == '') ? 'active' : '' }}"><a href="{{ route('trips.index') }}"><i class="fa fa-dashboard"></i><span>  الرحلات </span></a></li>
                            @endpermission

                            @permission('trips-read')
                                <li class="{{ (request()->segment(1) == '') ? 'active' : '' }}"><a href="{{ route('trips.archive') }}"><i class="fa fa-dashboard"></i><span>  الارشيف </span></a></li>
                            @endpermission

                        </ul>
                    </li>
            {{-- @endpermission  --}}
        </ul>
    </section>
</aside>
