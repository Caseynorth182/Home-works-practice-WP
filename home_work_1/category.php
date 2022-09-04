<?php
get_header();
$cash_response = api_request();
$cash_sort_arr = [];
if ($cash_response && !empty($cash_response)) {
	foreach ($cash_response as $cash_single) {
		if ($cash_single->cc == 'EUR' || $cash_single->cc == "USD") {
			$cash_sort_arr[] = $cash_single;
		}
	}
}
?>

<?php
$query = new WP_Query([
	'cat' => get_the_id(),
]);
?>
<div class="container">
    <div class="black" style="">
        <?php
		foreach ($cash_sort_arr as $cash) :
		?>
        <span><?= $cash->txt ?></span>
        <ul class="list-group">
            <li class="list-group-item"><?= $cash->rate ?></li>
            <li class="list-group-item"><?= $cash->exchangedate ?></li>

        </ul>
        <?php endforeach; ?>
    </div>
    <div class="row">
        <?php
		// проверяем есть ли посты в глобальном запросе - переменная $wp_query
		if ($query->have_posts()) {
			// перебираем все имеющиеся посты и выводим их
			while ($query->have_posts()) {
				$query->the_post();
				// var_dump(get_the_post_thumbnail(the_ID()));
		?>

        <div class="col-md-4 p-2">
            <div class="card ">
                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID()) ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php the_title(); ?></h5>
                    <!-- <p class="card-text"><?php the_content(); ?></p> -->
                    <a href="<?php the_permalink(); ?>" class="btn btn-primary">Go somewhere</a>
                </div>
            </div>
        </div>



        <?php
			}
			?>

        <div class="navigation">
            <div class="next-posts"><?php next_posts_link(); ?></div>
            <div class="prev-posts"><?php previous_posts_link(); ?></div>
        </div>

        <?php
		}
		// постов нет
		else {
			echo "<h2>Записей нет.</h2>";
		}
		?>
    </div>
</div>
<?php get_footer() ?>