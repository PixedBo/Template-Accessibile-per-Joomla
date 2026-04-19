<?php
/**
 * @package     TemplateAccessibilePerJoomla
 * @subpackage  Layouts
 *
 * Widget "Valutazione chiarezza pagina" (C.SI.2.5 / C.SI.2.6 Modello Comuni).
 * Emette i data-element richiesti dall'App Valutazione Modelli:
 *   feedback, feedback-title, feedback-rate-1..5,
 *   feedback-rating-positive, feedback-rating-negative,
 *   feedback-rating-question (x2), feedback-rating-answer (x10),
 *   feedback-input-text.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

$app        = Factory::getApplication();
$template   = $app->getTemplate();
$spritePath = Uri::base(true) . '/templates/' . $template . '/svg/sprites.svg';

$uid       = uniqid('rating_');
$starName  = 'ratingA_' . $uid;
$posName   = 'rating1_' . $uid;
$negName   = 'rating2_' . $uid;
$detailId  = 'feedback-detail-' . $uid;

$positiveAnswers = [
    Text::_('TPL_ACCESSIBILE_FEEDBACK_POS_1'),
    Text::_('TPL_ACCESSIBILE_FEEDBACK_POS_2'),
    Text::_('TPL_ACCESSIBILE_FEEDBACK_POS_3'),
    Text::_('TPL_ACCESSIBILE_FEEDBACK_POS_4'),
    Text::_('TPL_ACCESSIBILE_FEEDBACK_POS_5'),
];
$negativeAnswers = [
    Text::_('TPL_ACCESSIBILE_FEEDBACK_NEG_1'),
    Text::_('TPL_ACCESSIBILE_FEEDBACK_NEG_2'),
    Text::_('TPL_ACCESSIBILE_FEEDBACK_NEG_3'),
    Text::_('TPL_ACCESSIBILE_FEEDBACK_NEG_4'),
    Text::_('TPL_ACCESSIBILE_FEEDBACK_NEG_5'),
];
?>
<div class="bg-primary tpl-accessibile-feedback-wrapper">
    <div class="container">
        <div class="row d-flex justify-content-center bg-primary">
            <div class="col-12 col-lg-6 p-lg-0 px-3">
                <div class="cmp-rating pt-lg-80 pb-lg-80" id="<?php echo $uid; ?>" data-feedback-widget>
                    <div class="card shadow card-wrapper" data-element="feedback">

                        <div class="alert alert-warning rounded-0 m-0 mb-0 border-0 border-bottom" role="status">
                            <strong class="d-block mb-1"><?php echo Text::_('TPL_ACCESSIBILE_FEEDBACK_PLACEHOLDER_TITLE'); ?></strong>
                            <span class="small"><?php echo Text::_('TPL_ACCESSIBILE_FEEDBACK_PLACEHOLDER_DESC'); ?></span>
                        </div>

                        <div class="cmp-rating__card-first" data-step="0">
                            <div class="card-header border-0">
                                <h2 class="title-medium-2-semi-bold mb-0" data-element="feedback-title">
                                    <?php echo Text::_('TPL_ACCESSIBILE_FEEDBACK_TITLE'); ?>
                                </h2>
                            </div>
                            <div class="card-body">
                                <fieldset class="rating">
                                    <legend class="visually-hidden"><?php echo Text::_('TPL_ACCESSIBILE_FEEDBACK_TITLE'); ?></legend>
                                    <?php for ($i = 5; $i >= 1; $i--) :
                                        $radioId = 'star' . $i . 'a_' . $uid;
                                    ?>
                                    <input type="radio" class="rating-star" id="<?php echo $radioId; ?>" name="<?php echo $starName; ?>" value="<?php echo $i; ?>">
                                    <label for="<?php echo $radioId; ?>" data-element="feedback-rate-<?php echo $i; ?>">
                                        <span class="visually-hidden"><?php echo Text::sprintf('TPL_ACCESSIBILE_FEEDBACK_RATE_N_OF_5', $i); ?></span>
                                        <svg class="icon icon-primary" aria-hidden="true">
                                            <use href="<?php echo $spritePath; ?>#it-star-full"></use>
                                        </svg>
                                    </label>
                                    <?php endfor; ?>
                                </fieldset>
                            </div>
                        </div>

                        <div class="cmp-rating__card-second d-none" data-step="3">
                            <div class="card-body text-center py-4">
                                <p class="h5 mb-0"><?php echo Text::_('TPL_ACCESSIBILE_FEEDBACK_THANKS'); ?></p>
                            </div>
                        </div>

                        <div class="form-rating d-none">

                            <div class="d-none rating-shadow" data-step="1">
                                <div class="cmp-steps-rating">
                                    <p class="text-muted small mb-3 px-3 pt-3"><?php echo Text::sprintf('TPL_ACCESSIBILE_FEEDBACK_STEP', 1); ?></p>

                                    <fieldset class="fieldset-rating-one d-none px-3 pb-3" data-element="feedback-rating-positive">
                                        <legend>
                                            <span data-element="feedback-rating-question"><?php echo Text::_('TPL_ACCESSIBILE_FEEDBACK_Q_POSITIVE'); ?></span>
                                        </legend>
                                        <div class="cmp-radio-list">
                                            <?php foreach ($positiveAnswers as $idx => $ans) :
                                                $radioId = 'rating1_' . ($idx + 1) . '_' . $uid;
                                            ?>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" id="<?php echo $radioId; ?>" name="<?php echo $posName; ?>" value="<?php echo $idx + 1; ?>">
                                                <label class="form-check-label" for="<?php echo $radioId; ?>" data-element="feedback-rating-answer">
                                                    <?php echo $ans; ?>
                                                </label>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </fieldset>

                                    <fieldset class="fieldset-rating-two d-none px-3 pb-3" data-element="feedback-rating-negative">
                                        <legend>
                                            <span data-element="feedback-rating-question"><?php echo Text::_('TPL_ACCESSIBILE_FEEDBACK_Q_NEGATIVE'); ?></span>
                                        </legend>
                                        <div class="cmp-radio-list">
                                            <?php foreach ($negativeAnswers as $idx => $ans) :
                                                $radioId = 'rating2_' . ($idx + 1) . '_' . $uid;
                                            ?>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" id="<?php echo $radioId; ?>" name="<?php echo $negName; ?>" value="<?php echo $idx + 1; ?>">
                                                <label class="form-check-label" for="<?php echo $radioId; ?>" data-element="feedback-rating-answer">
                                                    <?php echo $ans; ?>
                                                </label>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="d-none px-3 pb-3" data-step="2">
                                <p class="text-muted small mb-3 pt-3"><?php echo Text::sprintf('TPL_ACCESSIBILE_FEEDBACK_STEP', 2); ?></p>
                                <label for="<?php echo $detailId; ?>" class="form-label fw-semibold">
                                    <?php echo Text::_('TPL_ACCESSIBILE_FEEDBACK_ADDITIONAL'); ?>
                                </label>
                                <input type="text" class="form-control" id="<?php echo $detailId; ?>" maxlength="200" data-element="feedback-input-text" aria-describedby="<?php echo $detailId; ?>-help">
                                <small id="<?php echo $detailId; ?>-help" class="form-text text-muted">
                                    <?php echo Text::_('TPL_ACCESSIBILE_FEEDBACK_DETAIL_HELP'); ?>
                                </small>
                            </div>

                            <div class="d-flex justify-content-between gap-2 px-3 pb-3">
                                <button type="button" class="btn btn-outline-primary btn-back">
                                    <?php echo Text::_('TPL_ACCESSIBILE_FEEDBACK_BACK'); ?>
                                </button>
                                <button type="button" class="btn btn-primary btn-next">
                                    <?php echo Text::_('TPL_ACCESSIBILE_FEEDBACK_NEXT'); ?>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
