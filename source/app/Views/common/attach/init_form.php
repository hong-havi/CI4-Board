
<div class="attach-forms attach-st1">
    <div class="att-top">
        <div class="att-top-l">
            첨부파일&nbsp;&nbsp;
            <select class="select_st1">
                <option value="">전체</option>
                <option value="">디자인</option>
                <option value="">기획</option>
                <option value="">기타</option>
            </select>
        </div>
        <a href="javascript:void(0)" class="Att-refreash"><i class="sjwi-refreash"></i></a>
        <?php if( $mode == 'write' ){ ?>
        <div class="att-top-r">                 
            <button class="btn btn-dark btn-sm uploadbtn" type="button" >파일첨부</buton>
        </div>
        <?php } ?>
    </div>
    <div class="att-body">
        <table class="table att-table ">
            <colgroup>
                <col style="width:110px"/>
                <col style="width:100px"/>
                <col style="width:75px"/>
                <col />
                <col style="width:140px" />
                <col style="width:180px" />
            </colgroup>
            <tbody>
            </tbody>
        </table>
    </div>
</div>