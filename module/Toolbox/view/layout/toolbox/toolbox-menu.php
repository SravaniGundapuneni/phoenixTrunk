<?php 
	if (isset($subsiteRoute)) {
		$homeUrl = urldecode($this->url('root-subsite', array('subsite' => $subsiteRoute)));
	} else {
		$homeUrl = $this->url('home');
	}
?>
<link href="<?php echo $this->toolboxIncludeUrl;?>/module/Toolbox/view/layout/css/toolbox.css" rel="stylesheet"/>
<link href="<?php echo $this->toolboxIncludeUrl;?>/module/Toolbox/view/layout/css/vendor/foundation/foundation.css" rel="stylesheet"/>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<script src="<?php echo $this->toolboxIncludeUrl;?>/module/Toolbox/view/layout/js/jquery.min.js"></script>
<script src="<?php echo $this->toolboxIncludeUrl;?>/module/Toolbox/view/layout/js/foundation/foundation.js"></script>
<script src="<?php echo $this->toolboxIncludeUrl;?>/module/Toolbox/view/layout/js/foundation/foundation.tooltip.js"></script>
<script src="<?php echo $this->toolboxIncludeUrl;?>/module/Toolbox/view/layout/js/foundation/foundation.topbar.js"></script>
<script src="<?=$toolboxIncludeUrl?>/module/Toolbox/view/layout/js/ckeditor/ckeditor.js"></script>
<header class="toolboxAdminToolbar header">
	<div class="container">

		<div class="row">
			<nav class="toolbox nav-bar large-12 columns" data-topbar role="navigation">
				<ul class="toolbox title-area">
					<li class="toolbox name">
						<a href="<?php echo $this->url($toolboxRootRoute, array('subsite' => $subsiteRoute));?>" title="Toolbox CMS">
							<img src="<?php echo $this->toolboxIncludeUrl;?>/module/Toolbox/view/layout/img/dashboard_logo.png" alt="Toolbox CMS" />
							<h1>Phoenix Toolbox</h1>
						</a>
					</li>
				</ul>

				<section class="toolbox nav-bar-section">
					<!-- Right Nav Section -->
					<ul class="toolbox right">
						<li class= "toolbox"><a href="tools.html">Help</a></li>
						<li class="toolbox has-dropdown"><a data-toggle="toolbox" data-hover="dropdown" class="welcome" href="#">Welcome, <?= $this->currentUser['givenName'] ?> <b class="caret"></b></a>
							<ul class="dropdown">
								<li><a href="<?php echo $this->url($toolboxRootRoute, array('subsite' => $subsiteRoute)); ?>tools/users/changeProfile/<?php echo $this->currentUser['userId'] ?>"><i class="fa fa-user"></i>Profile</a></li>
								<li><a href="<?php echo urldecode($this->url($toolboxRootRoute, array('subsite' => $subsiteRoute))); ?>?logout=1"><i class="fa fa-sign-out"></i>Logout</a></li>
							</ul>
						</li>
					</ul>

					<!-- Left Nav Section -->
					<ul class="toolbox left">
						<li class="toolbox"><a href="<?php echo $this->url($toolboxRootRoute, array('subsite' => $subsiteRoute));?>">Home</a></li>
						<li class="toolbox has-dropdown">
							<a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" href="#">Content Tools <b class="caret"></b></a>
							<ul class="toolbox dropdown">
								<?= $this->getDropdownNavigation(count($this->toolList), 'up', 10) ?>
								<li class="has-dropdown">
									<a href="#" data-dropdown="dlmDrop" data-toggle="dropdown" data-hover="dropdown" data-options="align: right" class="data-toggle">DLM</a>
									<ul id="dlmDrop" class="toolbox dropdown f-dropdown drop-right">
										<?= $this->getDropdownNavigation(count($this->dlmToolList), 'up') ?>
										<?php foreach($this->dlmToolList as $toolName => $toolPath):?>
											<li><a href="<?php echo $toolboxHomeUrl . $toolPath;?>"><?= trim(str_replace('DLM', '', $this->camelCaseToWords($toolName))); ?></a></li>
										<?php endforeach;?>
										<?= $this->getDropdownNavigation(count($this->dlmToolList), 'down') ?>
									</ul>
								</li>
								<li><a href="<?php echo $homeUrl;?>">Site Content</a></li>
								<?php foreach($this->toolList as $toolName => $toolPath):?>
									<li><a href="<?php echo $toolboxHomeUrl . $toolPath;?>"><?php echo $toolName;?></a></li>
								<?php endforeach;?>
								<?= $this->getDropdownNavigation(count($this->toolList), 'down', 10) ?>
							</ul>
						</li>

						<?php if ($this->isAdmin):?>
							<li class="toolbox has-dropdown">
								<a data-toggle="dropdown" data-hover="dropdown" class="" href="#">Admin Tools <b class="caret"></b></a>
								<ul class="toolbox dropdown">
									<?= $this->getDropdownNavigation(count($this->adminToolList), 'up') ?>
									<?php foreach($this->adminToolList as $toolName => $toolPath):?>
										<li><a href="<?php echo $toolboxHomeUrl . $toolPath;?>"><?php echo $toolName;?></a></li>
									<?php endforeach;?>
									<?= $this->getDropdownNavigation(count($this->adminToolList), 'down') ?>
								</ul>
							</li>
						<?php endif;?>

						<?php if ($this->isDeveloper):?>
							<li class="toolbox has-dropdown">
								<a data-toggle="dropdown" data-hover="dropdown" class="" href="#">Dev Tools <b class="caret"></b></a>
								<ul class="toolbox dropdown">
									<?= $this->getDropdownNavigation(count($this->devToolList), 'up') ?>
									<?php foreach($this->devToolList as $toolName => $toolPath):?>
										<li><a href="<?php echo $toolboxHomeUrl . $toolPath;?>"><?php echo $toolName;?></a></li>
									<?php endforeach;?>
									<?= $this->getDropdownNavigation(count($this->devToolList), 'down') ?>
								</ul>
							</li> 
						<?php endif;?>

						<?php //if ($this->editPage == true):?>
							<li class="editPageLi editor-control">
								<a id="runEditor" href='#' title="Edit">
									<span class="fa-stack fa-sm">
										<i class="fa fa-square fa-stack-2x"></i>
										<i class="fa fa-edit fa-stack-1x fa-inverse"></i>
									</span>
								</a>
							</li>
							<li class="savePageLi editor-control" style="display: none;" >
								<a id="clickToSave" href='#' title="Save">
									<span class="fa-stack fa-sm">
										<i class="fa fa-square fa-stack-2x"></i>
										<i class="fa fa-save fa-stack-1x fa-inverse"></i>
									</span>
								</a>
							</li>
							<li class="cancelPageLi editor-control" style="display: none;" >
								<a id="cancelPage" href='#' title="Cancel">
									<span class="fa-stack fa-sm">
										<i class="fa fa-square fa-stack-2x"></i>
										<i class="fa fa-times fa-stack-1x fa-inverse"></i>
									</span>
								</a>
							</li>
							<input type="hidden" id="pageID" name="pageID" value="<?php echo $this->pageKey; ?>" />
							<input type="hidden" id="currentPage" name="currentPage" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
							<input type="hidden" id="dataSection" name="dataSection" value="<?php echo $currentDataSection; ?>" />
						<?php //endif;?>
					</ul>
				</section>
			</nav>
		</div>
	</div>
	<script>

		/**
		 * TODO: This is copied over from the main layout file.
		 * 		 This *should* live in it's own file that the template preview
		 * 		 can cue up to load after jquery when rendering the toolbar.
		 * 		 ... should also remove the other script tags at the top of the
		 * 		     page and load through the same process.
		 */
		function initDropdownScrolling() {
			$('.dropdown-navigation-down').each(function () {
				var downBtn = $(this),
					upBtn = downBtn.siblings('.dropdown-navigation-up'),
					items = downBtn.siblings(':not(.dropdown-navigation-up, .js-generated)'),
					limit = downBtn.data('item-limit');

				// hide anything over the limit
				items.each(function (index, elem) {
					if (index + 1 > limit) {
						$(elem).hide();
					}
				});

				upBtn.addClass('disabled').unbind('click').click(function () {
					if (!upBtn.hasClass('disabled')) {
						downBtn.removeClass('disabled');

						items.filter(':visible:first').prev().show();
						items.filter(':visible:last').hide();

						if (items.filter(':first').is(':visible')) {
							upBtn.addClass('disabled');
						}
					}
				});

				downBtn.unbind('click').click(function () {
					if (!downBtn.hasClass('disabled')) {
						upBtn.removeClass('disabled');


						items.filter(':visible:last').next().show();
						items.filter(':visible:first').hide();

						if (items.filter(':last').is(':visible')) {
							downBtn.addClass('disabled');
						}
					}
				});
			});
		}

		$(function () {
			initDropdownScrolling();
			$(document).foundation();
		});
	</script>
</header>
