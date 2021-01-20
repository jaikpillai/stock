<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">

      <li id="dashboardMainMenu">
        <a href="<?php echo base_url('dashboard') ?>">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>

   

       












        <!-- Category -->




        <?php if (in_array('createStore', $user_permission) || in_array('updateStore', $user_permission) || in_array('viewStore', $user_permission) || in_array('deleteStore', $user_permission)) : ?>
          <!-- Stores -->

          <!-- <li id="storeNav">
              <a href="<?php echo base_url('stores/') ?>">
                <i class="fa fa-files-o"></i> <span>Stores</span>
              </a>
            </li> -->
        <?php endif; ?>

        <?php if (in_array('createAttribute', $user_permission) || in_array('updateAttribute', $user_permission) || in_array('viewAttribute', $user_permission) || in_array('deleteAttribute', $user_permission)) : ?>
          <!-- Attributes -->

          <!-- <li id="attributeNav">
            <a href="<?php echo base_url('attributes/') ?>">
              <i class="fa fa-files-o"></i> <span>Attributes</span>
            </a>
          </li> -->
        <?php endif; ?>



        <?php if (in_array('createProduct', $user_permission) || in_array('updateProduct', $user_permission) || in_array('viewProduct', $user_permission) || in_array('deleteProduct', $user_permission)) : ?>
          <li class="treeview" id="mainAdmin">
            <a href="#">
              <i class="fa fa-lock"></i>
              <span>Admin</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>



            <ul class="treeview-menu">

            <?php if (in_array('createGroup', $user_permission) || in_array('updateGroup', $user_permission) || in_array('viewGroup', $user_permission) || in_array('deleteGroup', $user_permission)) : ?>
          <li class="treeview" id="mainGroupNav">
            <!-- Groups -->

            <a href="#">
              <i class="fa fa-files-o"></i>
              <span>Groups</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <?php if (in_array('createGroup', $user_permission)) : ?>
                <li id="addGroupNav"><a href="<?php echo base_url('groups/create') ?>"><i class="fa fa-circle-o"></i> Add Group</a></li>
              <?php endif; ?>
              <?php if (in_array('updateGroup', $user_permission) || in_array('viewGroup', $user_permission) || in_array('deleteGroup', $user_permission)) : ?>
                <li id="manageGroupNav"><a href="<?php echo base_url('groups') ?>"><i class="fa fa-circle-o"></i> Manage Groups</a></li>
              <?php endif; ?>
            </ul>
          </li>
        <?php endif; ?>

          

        <?php if ($user_permission) : ?>
        <?php if (in_array('createUser', $user_permission) || in_array('updateUser', $user_permission) || in_array('viewUser', $user_permission) || in_array('deleteUser', $user_permission)) : ?>
          <li class="treeview" id="mainUserNav">
            <!-- Add users and mange users -->

            <a href="#">
              <i class="fa fa-users"></i>
              <span>Users</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <?php if (in_array('createUser', $user_permission)) : ?>
                <li id="createUserNav"><a href="<?php echo base_url('users/create') ?>"><i class="fa fa-circle-o"></i> Add User</a></li>
              <?php endif; ?>

              <?php if (in_array('updateUser', $user_permission) || in_array('viewUser', $user_permission) || in_array('deleteUser', $user_permission)) : ?>
                <li id="manageUserNav"><a href="<?php echo base_url('users') ?>"><i class="fa fa-circle-o"></i> Manage Users</a></li>
              <?php endif; ?>
            </ul>
          </li>
        <?php endif; ?>

        <?php if (in_array('updateCompany', $user_permission)) : ?>
          <li id="companyNav"><a href="<?php echo base_url('company/') ?>"><i class="fa fa-files-o"></i> <span>Company</span></a></li>
        <?php endif; ?>



        <!-- <li class="header">Settings</li> -->

        <?php if (in_array('viewProfile', $user_permission)) : ?>
          <li id = "manageProfile"><a href="<?php echo base_url('users/profile/') ?>"><i class="fa fa-user-o"></i> <span>Profile</span></a></li>
        <?php endif; ?>
        <?php if (in_array('updateSetting', $user_permission)) : ?>
          <li id="settings"> <a href="<?php echo base_url('users/setting/') ?>"><i class="fa fa-wrench"></i> <span>Setting</span></a></li>
        <?php endif; ?>

            </ul>


          </li>
        <?php endif; ?>



        <?php if (in_array('createProduct', $user_permission) || in_array('updateProduct', $user_permission) || in_array('viewProduct', $user_permission) || in_array('deleteProduct', $user_permission)) : ?>
          <li class="treeview" id="mainMasterForm">
            <a href="#">
              <i class="fa fa-cube"></i>
              <span>Master Form</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>



            <ul class="treeview-menu">


              <li id="categoryNav">
                <a href="<?php echo base_url('category/') ?>">
                  <i class="fa fa-files-o"></i> <span>Category</span>
                </a>
              </li>
            <?php endif; ?>


            <li id="taxNav">
              <a href="<?php echo base_url('tax/') ?>">
                <i class="fa fa-sticky-note-o"></i> <span>Tax master</span>
              </a>
            </li>

            <li id="mainProductNav">
            <?php if (in_array('updateProduct', $user_permission) || in_array('viewProduct', $user_permission) || in_array('deleteProduct', $user_permission)) : ?>
                  <a href="<?php echo base_url('products') ?>"><i class="fa fa-cube"></i> <span>Item master</span></a>
                <?php endif; ?>
              </a>
            </li>


            <!-- <li class="treeview" id="mainProductNav">
              <a href="#">
                <i class="fa fa-cube"></i>
                <span>Item Master</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">

                <?php if (in_array('createProduct', $user_permission)) : ?>
                  <li id="addProductNav"><a href="<?php echo base_url('products/create') ?>"><i class="fa fa-circle-o"></i> Add Item</a></li>
                <?php endif; ?>
                <?php if (in_array('updateProduct', $user_permission) || in_array('viewProduct', $user_permission) || in_array('deleteProduct', $user_permission)) : ?>
                  <li id="manageProductNav"><a href="<?php echo base_url('products') ?>"><i class="fa fa-circle-o"></i> Manage Items</a></li>
                <?php endif; ?>
              </ul>

            </li> -->

            <!-- Party Master -->
            <?php if (in_array('createOrder', $user_permission) || in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)) : ?>
              <li id="mainPartyNav">
              <?php if (in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)) : ?>
                    <a href="<?php echo base_url('party') ?>"><i class="fa fa-user"></i>Party Master</a>
                  <?php endif; ?>
              </a>
            </li>
              
              
            <?php endif; ?>

            <?php if (in_array('createCategory', $user_permission) || in_array('updateCategory', $user_permission) || in_array('viewCategory', $user_permission) || in_array('deleteCategory', $user_permission)) : ?>
              <!-- Category -->



            </ul>


          </li>
        <?php endif; ?>




        <?php if (in_array('createOrder', $user_permission) || in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)) : ?>
          <li class="treeview" id="mainOrderManage">
            <a href="#">
              <i class="fa fa-inr"></i>
              <span>Order Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">

            <!-- Invoice Transaction  -->
              <?php if (in_array('createOrder', $user_permission) || in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)) : ?>
                <li class="treeview" id="mainOrderNav">
                  <a href="#">
                    <i class="fa fa-inr"></i>
                    <span>Invoice Transaction</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <?php if (in_array('createOrder', $user_permission)) : ?>
                      <li id="addOrderNav"><a href="<?php echo base_url('orders/create') ?>"><i class="fa fa-circle-o"></i> Add Order</a></li>
                    <?php endif; ?>
                    <?php if (in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)) : ?>
                      <li id="manageOrderNav"><a href="<?php echo base_url('orders/') ?>"><i class="fa fa-circle-o"></i> Manage Orders</a></li>
                    <?php endif; ?>
                  </ul>
                </li>
              <?php endif; ?>


              <!-- Purchase Transaction -->
              <?php if (in_array('createOrder', $user_permission) || in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)) : ?>
                <li class="treeview" id="mainPurchaseNav">
                  <a href="#">
                    <i class="fa fa-handshake-o"></i>
                    <span>Purchase Transaction</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <?php if (in_array('createOrder', $user_permission)) : ?>
                      <li id="addPurchaseNav"><a href="<?php echo base_url('purchase/create') ?>"><i class="fa fa-circle-o"></i> Add Purchase</a></li>
                    <?php endif; ?>
                    <?php if (in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)) : ?>
                      <li id="managePurchaseNav"><a href="<?php echo base_url('purchase') ?>"><i class="fa fa-circle-o"></i> Manage Purchase</a></li>
                    <?php endif; ?>
                  </ul>
                </li>
              <?php endif; ?>

              <!-- Quotation Transaction -->
              <?php if (in_array('createOrder', $user_permission) || in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)) : ?>
                <li class="treeview" id="mainQuotationNav">
                  <a href="#">
                    <i class="fa fa-briefcase"></i>
                    <span>Quotation Transaction</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <?php if (in_array('createOrder', $user_permission)) : ?>
                      <li id="addQuotationNav"><a href="<?php echo base_url('quotation/create') ?>"><i class="fa fa-circle-o"></i> Add Quotation</a></li>
                    <?php endif; ?>
                    <?php if (in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)) : ?>
                      <li id="manageQuotationNav"><a href="<?php echo base_url('quotation') ?>"><i class="fa fa-circle-o"></i> Manage Quotations</a></li>
                    <?php endif; ?>
                  </ul>
                </li>
              <?php endif; ?>


            </ul>
          </li>
        <?php endif; ?>











        <?php if (in_array('viewReports', $user_permission)) : ?>
          <li id="reportNav">
            <a href="<?php echo base_url('reports/') ?>">
              <i class="glyphicon glyphicon-stats"></i> <span>Reports</span>
            </a>
          </li>
        <?php endif; ?>





       

      <?php endif; ?>
      <!-- user permission info -->

    </ul>
  </section>
  <!-- /.sidebar -->
</aside>