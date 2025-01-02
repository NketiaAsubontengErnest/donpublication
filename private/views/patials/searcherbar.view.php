<form method="get">
    <div class="input-group col-lg-4">
        <?php if ($hiddenSearch == '') : ?>
            <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                <span class="input-group-text" id="search">
                    <i class="icon-search"></i>
                </span>
            </div>
            <input type="text" class="form-control" id="navbar-search-input" name="search_box" placeholder="Search now" aria-label="search" aria-describedby="search">
        <?php endif; ?>
    </div>
</form>