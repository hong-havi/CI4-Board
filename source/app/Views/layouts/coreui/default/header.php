<header class="app-header navbar">
    <button class="navbar-toggler mobile-sidebar-toggler d-lg-none" type="button">
      <span ></span>
    </button>
    <a class="navbar-brand" href="/"></a>
    
    <ul class="nav navbar-nav ml-auto">

      <li>
        <div class="search ml-auto">
          <input type="text" class="input_search" name="search_val" id="search_val" placeholder="통합 검색">
          <a href="javascript:;" onclick="#" class="submit_search">검색</a>
        </div>
      </li>

      <li class="nav-item dropdown d-md-down-none m">
        <a class="nav-link" data-toggle="dropdown" href="javascript:;" onclick="SearchPeople.search('open')" role="button" aria-haspopup="true" aria-expanded="false">
          <i class="sjwi sjwi-psearch" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="내선검색"></i>
        </a>
      </li>

      <li class="nav-item dropdown d-md-down-none m">
        <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" >
          <i class="sjwi sjwi-bell" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="알림"></i><span class="badge badge-pill">5</span>
        </a>
        
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg ">
            <div class="dropdown-header text-center">
              <strong>10,999,999개의 새로운 알림</strong>
            </div>
            <a href="#" class="dropdown-item small text-truncate">
            <i class="far fa-paper-plane text-info"></i> [수신] (공지) 2020년 7월~9월 촬영스케줄 공유 드립니다.
            </a>
            <a href="#" class="dropdown-item small text-truncate">
            <i class="far fa-paper-plane text-info"></i> [수신] (공지) 2020년 7월~9월 촬영스케줄 공유 드립니다.
            </a>
            <a href="#" class="dropdown-item small text-truncate">
            <i class="far fa-paper-plane text-info"></i> [수신] (공지) 2020년 7월~9월 촬영스케줄 공유 드립니다.
            </a>
            <a href="javascript:Paper.open()" class="dropdown-item text-center">
              <strong>더보기...</strong>
            </a>
          </div>
      </li>

      <li class="nav-item dropdown d-md-down-none m">
        <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" >
          <i class="sjwi sjwi-edms" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="전자결재" ></i><span class="badge badge-pill">45</span>
          
          <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header text-center">
              <strong>전자결재</strong>
            </div>
            <a href="#" class="dropdown-item">
              <div class="">결재
                <span class="float-right">
                  <strong>0/1 <span class="text-danger">(0/3)</span></strong>
                </span>
              </div>
            </a>
            <a href="#" class="dropdown-item">
              <div class="mb-1">검토
                <span class="float-right">
                  <strong>0/1</strong>
                </span>
              </div>
            </a>
            <a href="#" class="dropdown-item">
              <div class="mb-1">수신
                <span class="float-right">
                  <strong>0/1</strong>
                </span>
              </div>
            </a>
            <a href="#" class="dropdown-item">
              <div class="mb-1">참조
                <span class="float-right">
                  <strong>0/1</strong>
                </span>
              </div>
            </a>
            <a href="#" class="dropdown-item text-center">
              <strong>전자결재 보기</strong>
            </a>
          </div>
        </a>
      </li>
      
      <li class="nav-item dropdown d-md-down-none m">
        <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
          <i class="sjwi sjwi-expense" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Expense"></i>
        </a>
      </li>

      <li class="nav-item dropdown profile">
        <a class="nav-link nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <img src="//intra.sjwcorp.kr/_var/simbol/<?=$user['photo']?>" class="img-avatar" aria-hidden="true"><span class="user_name"><?=$user['name']?>님</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right profile_dd">
          <div class="dropdown-header myinfo">
            <strong>My Info</strong>
          </div>
          <span class="dropdown-item"><?=$user['name']?></span>
          <span class="dropdown-item">118.33.69.154</span>
          <span class="dropdown-item"><?=$user['tel1']?></span>
          <span class="dropdown-item"><?=$user['tel2']?></span>
          <span class="dropdown-item bn"><?=$user['email']?></span>
          <div class="dropdown-header settings">
            <strong>Settings</strong>
          </div>
          <a class="dropdown-item" href="#">연차캘린더</a>
          <a class="dropdown-item" href="#">근태관리</a>
          <a class="dropdown-item" href="/mypage/info">정보수정</a>
          <a class="dropdown-item" href="#">비밀번호 변경</a>
          <a class="dropdown-item bn" href="/accounts/logout">로그아웃</a>
        </div>
      </li>
      
      <button class="navbar-toggler aside-menu-toggler" type="button">
        <span></span>
      </button>

    </ul>
  </header>
