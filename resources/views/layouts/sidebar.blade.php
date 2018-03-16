<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">

  <!-- Sidebar user panel (optional) -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="{{ Auth::user()->getAvatar() }}" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p> {{ Auth::user()->name }}</p>
      <!-- Status -->
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>

  <!-- search form (Optional) -->
  <form action="#" method="get" class="sidebar-form">
    <div class="input-group">
      <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
            </button>
          </span>
    </div>
  </form>
  <!-- /.search form -->

  {!!

    $navigation->prefix('admin')->add([
      'MAIN NAVIGATION'=>['class'=>'header'],
      'Dashboard'=>['icon'=>'fa-dashboard','url'=>'/dashboard'],
      'ADMINISTRATOR'=>['class'=>'header'],
      'Content'=>['class'=>'treeview','icon'=>'fa-laptop','submenu'=>[
          'Pagine'=>'/pages','Web Content'=>'/content','Vocabolari'=>'/vocabularies',
          'Tags'=>'/tags','Dynamic Data List'=>'/ddl','Strutture'=>'/structure','Web Forms'=>'#',
          'Documenti e Immagini'=>'#']],
      'Manage'=>['class'=>'treeview','icon'=>'fa-link','submenu'=>[
          'Utenti'=>'/users','Gruppi'=>'/groups','Ruoli'=>'/roles','Permessi'=>'/permissions',
          'Organizzazioni'=>'/organizations','Sessioni attive'=>'/users/sessions',
          'Attività'=>'/users/activity']],
      'Portal'=>['class'=>'treeview','icon'=>'fa-edit','submenu'=>[
          'Portlets'=>'/portlets','Settings'=>'/settings','Siti'=>'#',
          'Aggiornamenti'=>'#']],
      'Services'=>['class'=>'treeview','icon'=>'fa-th','submenu'=>[
          'Blog'=>'#','Forum'=>'#','Wiki'=>'#','Newsletter'=>'#','Calendario eventi'=>'#',
          'Segnalibri'=>'#','Faq'=>'#','Sondaggi'=>'#','Centro Contatti'=>'#','Feed Rss'=>'#']]
      ])->render()

  !!}
  <!-- /.sidebar-menu -->

</section>
<!-- /.sidebar -->
</aside>