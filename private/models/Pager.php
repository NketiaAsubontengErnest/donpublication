<?php

/**
 * Pagination class
 */

class Pager
{
    public $links = array();
    public $offset = 0;
    public $page_number = 1;
    public $start = 1;
    public $end = 1;
    public $limit = 1;

    public function __construct($limit = 15, $extras = 1)
    {
        $this->limit = $limit;
        // Set pagenation and limmit
        $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page_number = $page_number < 1 ?  1 : $page_number;

        $this->start = $page_number - $extras;
        $this->end = $page_number + $extras;

        if( $this->start < 1){
            $this->start  = 1;
        }

        $this->offset = ($page_number - 1) * $limit;
        $this->page_number = $page_number;

        //create links
        $current_link = HOME . "/" . str_replace("url=", "", $_SERVER['QUERY_STRING']);
        $current_link = !strstr($current_link, "page=") ? $current_link . "&page=1" : $current_link;
        $first_link = preg_replace('/page=[0-9]+/', "page=1", $current_link);
        $next_link = preg_replace('/page=[0-9]+/', "page=".($page_number + $extras + 1), $current_link);

        $this->links['first'] = $first_link;
        $this->links['current'] = $current_link;

        $this->links['next'] = $next_link;

    }
    public function display($itimesCount)
    {
        ?>
        <nav aria-lable="page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item"><a class="page-link" href="<?=esc($this->links['first'])?>"> <b><</b> </a></li>
                <?php if($itimesCount != 0):?>
                    <?php for($x = $this->start; $x <= $this->end; $x++):?>
                        <li class="page-item <?=$x == $this->page_number ? ' active ' : ''?> "><a class="page-link" href="
                        <?=esc(preg_replace('/page=[0-9]+/', "page=".$x, $this->links['current']))?>
                        "><b><?=esc($x)?></b> </a></li>
                    <?php endfor?>
                    <?php if($itimesCount >= $this->limit):?>
                        <li class="page-item"><a class="page-link" href="<?=esc($this->links['next'])?>"> <b>></b> </a></li>
                    <?php endif?>
                <?php endif?>
            </ul>
        </nav>
        <?php
    }
}
