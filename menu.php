<div class="site-navbar py-2">

      <div class="container">
        <div class="d-flex align-items-center justify-content-between">
          <div class="logo">
            <div class="site-logo">
              <a href="index.php" class="js-logo-clone">Prime Care Pharmacy</a>
            </div>
          </div>
          <div class="main-nav d-none d-lg-block">
            <nav class="site-navigation text-right text-md-center" role="navigation">
              <ul class="site-menu js-clone-nav d-none d-lg-block">
                
                <?php if ((isset($_SESSION['username'])) AND ($_SESSION['type'] == 'customer')) { ?>
                <li class="has-children">
                  <a href="#">You</a>
                  <ul class="dropdown">
                    <li class="has-children">
                      <a href="#">My Orders</a>
                      <ul class="dropdown">
                        <li><a href="my_orders.php">Item Orders</a></li>
                        <li><a href="my_prescription_orders.php">With prescription</a></li>
                      </ul>
                    </li>
                    <li class="has-children">
                      <a href="#">My Cart</a>
                      <ul class="dropdown">
                        <li><a href="cart.php">Item Orders</a></li>
                        <li><a href="Prescription_list.php">With prescription</a></li>
                      </ul>
                    </li>
                  </ul>
                </li>
                <li><a href="prescription.php"><button  name="out" class="btn btn-warning waves-effect waves-light" >Prescription
                        </button> </a>
                </li>
                <li> 
                      <form action="logout.php" method="post" >
                        <button  name="out" class="btn btn-danger waves-effect waves-light" >
                        <span class="btn-label"><i class="fa  fa-power-off"></i></span> Logout
                        </button>
                      </form>
                </li>
              <?php } else { ?>
                <li class="active"><a href="">All Products</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="../login">Login</a></li>
               <?php } ?> 
              </ul>
            </nav>
          </div>
          <div class="icons">
            <?php if (isset($_SESSION['username'])) {?>
            <a href="cart.php" class="icons-btn d-inline-block js-search-open">
            </a>
            <a href="#" class="site-menu-toggle js-menu-toggle ml-3 d-inline-block d-lg-none"><span class="icon-menu"></span></a>
            <?php } else { ?>
              <a href="cart.php" class="icons-btn d-inline-block js-search-open">
            </a>
            <a href="#" class="site-menu-toggle js-menu-toggle ml-3 d-inline-block d-lg-none"><span class="icon-menu"></span></a>
             <?php } ?> 
          </div>
          
        </div>
      </div>
    </div>