<?php

// use Carbon\Carbon;

/**
 * Template Name: 一斉配信メール一覧
 *
 * @Template Post Type: post, page,
 *
 */
get_header(); ?>


<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>
<?php include(get_theme_file_path() . "/App/Controlpanel/lib/view/components/component-controlpanel-sidebar-users.php") ?>
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-common.css') ?> ">
<link rel="stylesheet" href="<?= rampup_css_path('page-controlpanel-meeting-list.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-sidebar.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-header.css') ?>">

<div class="project__wrap">

	<div class="space__block"></div>



	<div class="project__contents">

		<div class="contents__width">
			<section class="list__management3">
				<div class="management__box">
					<h2 class="management__title">一斉配信メール<span>6件</span></h2>

					<a href="/" class="management__addition">
						<div class="cross"></div>
						<p>新規作成</p>
					</a>
				</div>


				<div class="management__wrap">

					<div class="management__left">

						<!-- <div class="choice">
							<select required>
								<option value="" hidden>ステータス</option>
								<option value="1"></option>
								<option value="2"></option>
								<option value="3"></option>
								<option value="4"></option>
								<option value="5"></option>
							</select>
						</div> -->

						<div class="choice -none">
							<input class="select__placeholder" placeholder="申込日" type="text">
						</div>

						<!-- <div class="choice -none">
							<select required>
								<option value="" hidden>契約日</option>
								<option value="1"></option>
								<option value="2"></option>
								<option value="3"></option>
							</select>
						</div>

						<div class="choice">
							<select required>
								<option value="" hidden>担当者</option>
								<option value="1"></option>
								<option value="2"></option>
							</select>
						</div> -->

					</div>


					<div class="management__right">
						<form class="search-wrap" action="https://" method="get">
							<input type="hidden" name="method" value="keyword">
							<input type="text" name="keyword" value="" class="search-box" placeholder="キーワードで検索">
							<input type="hidden" name="sort" value="desc">
							<label for="search" class="search-button"></label>
							<input id="search" type="submit" value="">
						</form>

						<form class="search-wrap2" action="https://" method="get">
							<label for="sort" class="search-sort">
								<article class="search-sort_icon"></article>
								<span class="search-sort_text">更新順</span>
							</label>
							<input type="hidden" name="method" value="order">
							<input type="hidden" name="keyword" value="">
							<input type="hidden" name="sort" value="desc">
							<input id="sort" style="display:none;" type="submit" value="">
						</form>
					</div>

				</div>

			</section>











			<section class="list__wrap">

				<table class="list__table7">

					<thead class="table__box3">
						<tr>
							<th class="table__title -padding">送信日時</th>
							<th class="table__title">テンプレート名</th>
							<th class="table__title">送信数</th>
							<th class="table__title">送信者</th>
							<th class="table__title"></th>
						</tr>
					</thead>

					<tbody class="table__box4">
						<tr>
							<td class="table__padding2 -adjust">
								<p>2021/06/24</p>
							</td>
							<td>カード決済　リクエストメール</td>
							<td>100件</td>
							<td>三矢宏貴</td>
							<td class="table__padding"><a href="/" class="list__detail">詳しく見る</a></td>
						</tr>

						<tr>
							<td class="table__padding2 -adjust">
								<p>2021/06/24</p>
							</td>
							<td>カード決済　リクエストメール</td>
							<td>100件</td>
							<td>三矢宏貴</td>
							<td class="table__padding"><a href="/" class="list__detail">詳しく見る</a></td>
						</tr>

						<tr>
							<td class="table__padding2 -adjust">
								<p>2021/06/24</p>
							</td>
							<td>カード決済　リクエストメール</td>
							<td>100件</td>
							<td>三矢宏貴</td>
							<td class="table__padding"><a href="/" class="list__detail">詳しく見る</a></td>
						</tr>

						<tr>
							<td class="table__padding2 -adjust">
								<p>2021/06/24</p>
							</td>
							<td>カード決済　リクエストメール</td>
							<td>100件</td>
							<td>三矢宏貴</td>
							<td class="table__padding"><a href="/" class="list__detail">詳しく見る</a></td>
						</tr>

						<tr>
							<td class="table__padding2 -adjust">
								<p>2021/06/24</p>
							</td>
							<td>カード決済　リクエストメール</td>
							<td>100件</td>
							<td>三矢宏貴</td>
							<td class="table__padding"><a href="/" class="list__detail">詳しく見る</a></td>
						</tr>

						<tr>
							<td class="table__padding2 -adjust">
								<p>2021/06/24</p>
							</td>
							<td>カード決済　リクエストメール</td>
							<td>100件</td>
							<td>三矢宏貴</td>
							<td class="table__padding"><a href="/" class="list__detail">詳しく見る</a></td>
						</tr>

						<tr>
							<td class="table__padding2 -adjust">
								<p>2021/06/24</p>
							</td>
							<td>カード決済　リクエストメール</td>
							<td>100件</td>
							<td>三矢宏貴</td>
							<td class="table__padding"><a href="/" class="list__detail">詳しく見る</a></td>
						</tr>

						<tr>
							<td class="table__padding2 -adjust">
								<p>2021/06/24</p>
							</td>
							<td>カード決済　リクエストメール</td>
							<td>100件</td>
							<td>三矢宏貴</td>
							<td class="table__padding"><a href="/" class="list__detail">詳しく見る</a></td>
						</tr>

						<tr>
							<td class="table__padding2 -adjust">
								<p>2021/06/24</p>
							</td>
							<td>カード決済　リクエストメール</td>
							<td>100件</td>
							<td>三矢宏貴</td>
							<td class="table__padding"><a href="/" class="list__detail">詳しく見る</a></td>
						</tr>

					</tbody>

				</table>
			</section>
		</div>


	</div>
</div>
<?php get_footer();
