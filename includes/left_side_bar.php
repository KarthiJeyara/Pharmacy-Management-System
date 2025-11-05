<style type="text/css">
    
.theme-red .navbar {
    background-color: #f44336;
}

.theme-red .sidebar .menu .list li.active > :first-child i, .theme-red .sidebar .menu .list li.active > :first-child span {
    color: #f99638;
}
</style>



<!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <!-- <div class="user-info"></div> -->
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <!-- <li class="header"></li> -->
                    <li class="active">
                        <?php if ($user_type === 'admin'): ?>
                            <a href="admin_dashboard.php">
                                <i class="fa fa-home" style="padding-top: 7px;"></i>
                                <span>Admin Dashboard</span>
                            </a>
                            <a href="category.php">
                            <i class="fa fa-file" style="padding-top: 7px;"></i>
                            <span>Category</span>
                        </a>

                        <a href="product_list.php">
                            <i class="fa fa-file" style="padding-top: 7px;"></i>
                            <span>Products</span>
                        </a>

                        <a href="inventory_list.php">
                            <i class="fa fa-file" style="padding-top: 7px;"></i>
                            <span>Stock of Products</span>
                        </a>

                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="fa  fa-user" style="padding-top: 7px;"></i>
                            <span>Order List</span>
                        </a>

                        <ul class="ml-menu">
                            <li>
                                <a href="order_list.php">
                                    <i class="fa fa-file" style="padding-top: 7px;"></i>
                                    <span>Items</span>
                                </a>
                            </li>
                                    
                            <li>
                                <a href="order_list_prescription.php">
                                    <i class="fa fa-file" style="padding-top: 7px;"></i>
                                    <span>Prescription</span>
                                </a>
                            </li>
                        </ul>


                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="fa  fa-user" style="padding-top: 7px;"></i>
                            <span>Users</span>
                        </a>

                        <ul class="ml-menu">
                            <li>
                                <a href="employee.php">
                                    <i class="fa fa-file" style="padding-top: 7px;"></i>
                                    <span>Employee List</span>
                                </a>
                            </li>
                                    
                            <li>
                                <a href="customer_list.php">
                                    <i class="fa fa-file" style="padding-top: 7px;"></i>
                                    <span>Customer List</span>
                                </a>
                            </li>
                        </ul>
                    
                        <?php endif; ?>

                        <?php if ($user_type === 'pharmacist'): ?>
                            <a href="pharmacist_dashboard.php">
                                <i class="fa fa-home" style="padding-top: 7px;"></i>
                                <span>Dashboard</span>
                            </a>


                            <a href="javascript:void(0);" class="menu-toggle">
                            <i class="fa  fa-user" style="padding-top: 7px;"></i>
                            <span>Order List</span>
                        </a>

                        <ul class="ml-menu">
                            <li>
                                <a href="p_order_list.php">
                                    <i class="fa fa-file" style="padding-top: 7px;"></i>
                                    <span>Items</span>
                                </a>
                            </li>
                                    
                            <li>
                                <a href="p_order_list_prescription.php">
                                    <i class="fa fa-file" style="padding-top: 7px;"></i>
                                    <span>Prescription</span>
                                </a>
                            </li>
                        </ul>

                            <a href="p_profile.php">
                                <i class="fa fa-file" style="padding-top: 7px;"></i>
                                <span>My Profile</span>
                            </a>

                        <?php endif; ?>

                        <?php if ($user_type === 'customer'): ?>

                        <a href="myaccount.php">
                            <i class="fa fa-user" style="padding-top: 7px;"></i>
                            <span>My Account</span>
                        </a>

                        <a href="myorder.php">
                            <i class="fa fa-list" style="padding-top: 7px;"></i>
                            <span>My Orders</span>
                        </a>

                        <a href="">
                            <i class="fa fa-shopping-cart" style="padding-top: 7px;"></i>
                            <span>Cart</span>
                        </a>

                        <?php endif; ?>

                    </li>
                    
                    <!-- <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="fa  fa-user" style="padding-top: 7px;"></i>
                            <span></span>
                        </a>
                        <ul class="ml-menu">
                                     <li>
                                        <a ></a>
                                    </li>
                                    <li>
                                        <a href=""></a>
                                    </li>
                                </ul>
                        
                    </li>-->    
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; <a href="javascript:void(0);"><?php echo date("Y"); ?></a> Version 1.0.
                </div>
                <div class="version">
                    <b>All Right Reserved. </b> 
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->