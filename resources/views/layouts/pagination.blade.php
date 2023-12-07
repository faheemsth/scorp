<style>
    .pagination-footer {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        padding: 0 1.5rem;
        border: 1px solid #d4dada;
        margin: 0 10px;
    }

    .number_of_records {
        width: 150px;
    }

    .pagination {
        list-style-type: none;
        padding: 10px 0;
        display: inline-flex;
        justify-content: space-between;
        box-sizing: border-box;
    }

    .pagination li {
        box-sizing: border-box;
        padding-right: 10px;
    }

    .pagination li a {
        box-sizing: border-box;
        padding: 10px 12px;
        text-decoration: none;
        font-size: 12px;
        font-weight: bold;
        color: #616872;
        border-radius: 4px;
    }

    .pagination li a:hover {
        background-color: #d4dada;
    }

    .pagination .next a,
    .pagination .prev a {
        text-transform: uppercase;
        font-size: 12px;
    }

    .pagination .currentpage a {
        background-color: #e2e6e6;
        color: #fff;
    }

    .pagination .currentpage a:hover {
        background-color: #e2e6e6;
    }
    .desc{
        position: relative;
    }

    .desc > p{
        margin-top: 1rem;
    }
</style>
<div class="pagination-footer">
    <?php $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

    $num_results_on_page =50;
    $num_results_on_page = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
    $record_start = ( ($page - 1) * $num_results_on_page);
    $record_end = $record_start + $num_results_on_page;
    //$record_start += 1;
    ?>

    <div class="desc">

        <p class="">Showing <?= $record_start ?> to <?= $record_end ?> of <?= $total_pages ?> entries</p>

    </div>

    <?php
    if (ceil($total_pages / $num_results_on_page) > 0) : ?>




        <?php
        if (isset($_GET['page'])) {
            $all_params = $_GET;
            unset($all_params['page']);
            $all_params['num_results_on_page'] = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            $url = request()->url() . '?' . http_build_query($all_params);
        } else {
            $all_params = $_GET;
            $all_params['num_results_on_page'] = isset($_GET['num_results_on_page']) ? $_GET['num_results_on_page'] : $num_results_on_page;
            $url = request()->url() . '?' . http_build_query($all_params);
        }


        ?>
        <ul class="pagination">
            <?php if ($page > 1) : ?>
                <li class="prev"><a href="<?= $url ?>&1=1&page=<?php echo $page - 1 ?>">Prev</a></li>
            <?php endif; ?>

            <?php if ($page > 3) : ?>
                <li class="start"><a href="<?= $url ?>&1=1&page=1">1</a></li>
                <li class="dots">...</li>
            <?php endif; ?>

            <?php if ($page - 2 > 0) : ?><li class="page"><a href="<?= $url ?>&1=1&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a></li><?php endif; ?>
            <?php if ($page - 1 > 0) : ?><li class="page"><a href="<?= $url ?>&1=1&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a></li><?php endif; ?>

            <li class="currentpage"><a href="<?= $url ?>&1=1&page=<?php echo $page ?>"><?php echo $page ?></a></li>

            <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1) : ?><li class="page"><a href="<?= $url ?>&1=1&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a></li><?php endif; ?>
            <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1) : ?><li class="page"><a href="<?= $url ?>&1=1&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a></li><?php endif; ?>

            <?php if ($page < ceil($total_pages / $num_results_on_page) - 2) : ?>
                <li class="dots">...</li>
                <li class="end"><a href="<?= $url ?>&1=1&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a></li>
            <?php endif; ?>

            <?php if ($page < ceil($total_pages / $num_results_on_page)) : ?>
                <li class="next"><a href="<?= $url ?>&1=1&page=<?php echo $page + 1 ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>
</div>
