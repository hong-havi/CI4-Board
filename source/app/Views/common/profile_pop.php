<div class="pop-profile">
    <div class="pp-header">
        <div class="pp-photo">
            <a class="profile-img" href="javascript:;">
                <img src="//intra.sjwcorp.kr/_var/simbol/b3aaf1ad792ef63517c61638452fbb2c.jpg" class="img-avatar" >
            </a>
        </div>
        <div class="pp-detail">
            <div class="name"><?=$user_info['name']?></div>
            <div class="level">직급/직책 : <?=$user_info['lname']?></div>
        </div>
        <div class="pp-closebtn">
            <a href="javascript:;" onclick="javascript:$('.userinfopop').popover('hide')"><i class="sjwi-close_bold"></i></a>
        </div>
    </div>
    <div class="pp-info">
        <div class="ppi-item"><?=$user_info['gpname']?> <?=$user_info['gname']?></div>
        <div class="ppi-item"><?=$user_info['tel1']?></div>
        <div class="ppi-item"><?=$user_info['tel2']?></div>
        <div class="ppi-item"><?=$user_info['email']?></div>
        <div class="ppi-item">
            <div class="ppi-tit">직무</div>
            <div class="ppi-det">
                <p class="ppi-job"><?=$user_info['job_det']?></p>
                <p class="ppi-det"><?=nl2br($user_info['job_info'])?></p>
            </div>
        </div>
    </div>
</div>