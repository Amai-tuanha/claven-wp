<?php
/**
 * @var $help MM_WPFS_ContextHelp
 */

$relatedArticles = $help->getRelatedArticles();
$globalArticles  = $help->getGlobalArticles();

?>
<a class="wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 wpfs-page-header__btn js-tooltip js-open-help-dropdown" data-tooltip-content="help-tooltip">
    <span class="wpfs-icon-help"></span>
</a>
<div class="wpfs-tooltip-content" data-tooltip-id="help-tooltip">
    <div class="wpfs-info-tooltip"><?php esc_html_e( 'Find help', 'wp-full-stripe-admin' ); ?></div>
</div>

<div class="wpfs-help-dropdown js-help-dropdown">
    <div class="wpfs-help-dropdown__title"><?php esc_html_e( 'Related articles', 'wp-full-stripe-admin' ); ?></div>
    <ul class="wpfs-help-dropdown__list">
		<?php foreach ( $relatedArticles as $relatedArticle ): ?>
            <li class="wpfs-help-dropdown__item">
                <a class="wpfs-help-dropdown__link wpfs-btn wpfs-btn-link" target="_blank"
                   href="<?php echo esc_attr( $relatedArticle->getHref() ); ?>">
                    <span class="<?php echo esc_attr( $relatedArticle->getVisualType()->getCssClass() ); ?>"></span>
                    <?php echo esc_html( $relatedArticle->getCaption() ); ?>
                </a>
            </li>
		<?php endforeach; ?>
    </ul>
    <div class="wpfs-help-dropdown__title"><?php esc_html_e( 'More resources', 'wp-full-stripe-admin' ); ?></div>
    <ul class="wpfs-help-dropdown__list">
		<?php foreach ( $globalArticles as $globalArticle ): ?>
            <li class="wpfs-help-dropdown__item">
                <a class="wpfs-help-dropdown__link wpfs-btn wpfs-btn-link" target="_blank"
                   href="<?php echo esc_attr( $globalArticle->getHref() ); ?>">
                    <span class="<?php echo esc_attr( $globalArticle->getVisualType()->getCssClass() ); ?>"></span>
                    <?php echo esc_html( $globalArticle->getCaption() ); ?>
                </a>
            </li>
		<?php endforeach; ?>
    </ul>
</div>