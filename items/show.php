<?php
$title = metadata('item', 'display_title');
$itemFiles = $item->Files;
$imageFiles = array();
$audioFiles = array();
$otherFiles = array();
foreach ($itemFiles as $itemFile) {
    $mimeType = $itemFile->mime_type;
    if (strpos($mimeType, 'image') !== false) {
        $imageFiles[] = $itemFile;
    } else if ($mimeType == "audio/mpeg") {
        $audioFiles[] = $itemFile;
    } else {
        $otherFiles[] = $itemFile;
    }
}
$hasImages = (count($imageFiles) > 0);
if ($hasImages) {
    queue_css_file('chocolat');
    queue_js_file('modernizr', 'javascripts/vendor');
    queue_js_file('jquery.chocolat.min', 'js');
    queue_js_file('items-show', 'js');
}
echo head(array('title' => $title, 'bodyclass' => 'items show' .  (($hasImages) ? ' gallery' : '')));
?>

<div class="flex">

    <!-- The following displays all the images. -->
<?php if ($hasImages): ?>
    <div id="itemfiles" <?php echo (count($imageFiles) == 1) ? 'class="solo"' : ''; ?>>
        <div id="itemfiles-stage"></div>
        <div id="itemfiles-nav">
            <?php foreach ($imageFiles as $imageFile): ?>
            <a href="<?php echo $imageFile->getWebPath('original'); ?>" class="chocolat-image">
                <?php echo file_image('square_thumbnail', array(), $imageFile); ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

    <div class="item-metadata">
        <nav>
        <ul class="item-pagination navigation">
            <li id="previous-item" class="previous"><?php echo link_to_previous_item_show(); ?></li>
            <li id="next-item" class="next"><?php echo link_to_next_item_show(); ?></li>
        </ul>
        </nav>

        <h1><?php echo metadata('item', 'display_title'); ?></h1>

        <!-- The following allows the user to select an audio file. -->
        <?php if (count($audioFiles) > 0): ?>
            <div id="audio-media" class="element">
                <h3><?php echo __("Recordings"); ?></h3>
                <?php $selected = (!isset($_POST['track']) || $_POST['track'] == "") ? 1 : (int)$_POST['track']; ?>
                <form action="" method="post">
                    <select id="select-audio" name="track">
                        <?php for ($i = 1, $len = sizeof($audioFiles)+1; $i < $len; $i++) : ?>
                        <option value="<?php echo $i; ?>"<?php echo ($i == $selected) ? " selected" : ""; ?>>
                            <?php echo metadata($audioFiles[$i-1], 'display_title'); ?>
                        </option>
                        <?php endfor; ?>
                    </select>
                    &nbsp;<br />
                    <input type="submit" value="<?php echo __("Select recording"); ?>">
                </form>
                &nbsp;<br />
                <?php echo file_markup($audioFiles[$selected-1], array("preload"=>"auto"), NULL); ?>
            </div>
        <?php endif; ?>

        <!-- The following displays all the miscellaneous files. -->
        <?php if (count($otherFiles) > 0): ?>
            <div id="other-media" class="element">
                <h3><?php echo __("Other Media"); ?></h3>
                <?php foreach ($otherFiles as $otherFile): ?>
                    <div class="element-text">
                        <a href="<?php echo file_display_url($otherFile, 'original'); ?>"><?php echo metadata($otherFile, 'display_title'); ?> - <?php echo $otherFile->mime_type; ?></a>
                    </div>
                <?php endforeach; ?>
             </div>
        <?php endif; ?>

        <?php if (metadata('item', 'Collection Name')): ?>
            <div id="collection" class="element">
                <h3><?php echo __('Collection'); ?></h3>
                <div class="element-text"><p><?php echo link_to_collection_for_item(); ?></p></div>
            </div>
        <?php endif; ?>

        <?php echo all_element_texts('item'); ?>

        <!-- The following prints a list of all tags associated with the item -->
        <?php if (metadata('item', 'has tags')): ?>
        <div id="item-tags" class="element">
            <h3><?php echo __('Tags'); ?></h3>
            <div class="element-text"><?php echo tag_string('item'); ?></div>
        </div>
        <?php endif;?>

        <!-- The following prints a citation for this item. -->
        <div id="item-citation" class="element">
            <h3><?php echo __('Citation'); ?></h3>
            <div class="element-text"><?php echo metadata('item', 'citation', array('no_escape' => true)); ?></div>
        </div>

        <div id="item-output-formats" class="element">
            <h3><?php echo __('Output Formats'); ?></h3>
            <div class="element-text"><?php echo output_format_list(); ?></div>
        </div>

        <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>
    </div>

</div>

<?php echo foot(); ?>