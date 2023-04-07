<!DOCTYPE html>
<html lang="kr">
     <?= $this->include('layouts/'.$layout_Thema.'/default/header_tag') ?>
     <body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
          <?= $this->include('layouts/'.$layout_Thema.'/default/header') ?>
          <div class="app-body">
               <?= $this->include('layouts/'.$layout_Thema.'/default/sidebar_left') ?>

               <main class="main">
                    <?php if( $Breadcrumb ){ ?>
                    <div class="row mt-5 mb-5">
                         <div class="col-lg-12 text-center">
                              <h2 class="font-weight-bold"><?=$Breadcrumb?></h2>      
                              <?php if( count($BredTab) > 0 ){ ?>
                                   <ul class="nav tab-st1 justify-content-center mt-4">
                                        <?php foreach( $BredTab as $Bred_Tab_data ){ ?>
                                        <li class="nav-item">
                                             <a class="nav-link font-lg <?=(($Bred_Tab_data['active_flag'] == true) ? "active" :"")?>" href="<?=$Bred_Tab_data['link']?>"><?=$Bred_Tab_data['name']?></a>
                                        </li>
                                        <?php } ?>
                                   </ul>     
                              <?php } ?>                         
                         </div>
                    </div>
                    <?php } ?>

                    <div class="<?=$Container?>">
                         <?= $this->renderSection('content') ?>
                    </div>
               </main>
               <?= $this->include('layouts/'.$layout_Thema.'/default/sidebar_right') ?>
          </div>
          <?= $this->include('layouts/'.$layout_Thema.'/default/footer') ?>
     </body>
</html>
