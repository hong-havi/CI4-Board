<div class="search_bar ml-auto">
    <input type="text" class="input_search" name="smd_search_val" id="search_val" placeholder="이름 검색" onkeyup="Sender.find(this.value)">
    <a href="javascript:;" onclick="" class="submit_search">검색</a>
</div>

<div class="smd-target-tree">
    <span class="smd-title">대상자 선택</span>
    <div class="smd-tree scroll-bar">
        <?=$group_tree?>
    </div>
    <div class="smd-selector scroll-bar scroll-dark">
        <ul class="smd-selector-list">
        </ul>
    </div>
    <div class="smd-btn-area">
        <button type="button" class="btn btn-outline-dark btn-sm px-3 mr-2" data-dismiss="modal" aria-label="Close">닫기</button>
        <button class="btn btn-dark btn-sm px-3" onclick="Sender.submit();">적용</button>
    </div>
</div>