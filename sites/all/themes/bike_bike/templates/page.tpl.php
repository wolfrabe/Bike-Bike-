<?php
/**
 * @file
 * Adaptivetheme implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * Adaptivetheme supplied variables:
 * - $site_logo: Themed logo - linked to front with alt attribute.
 * - $site_name: Site name linked to the homepage.
 * - $site_name_unlinked: Site name without any link.
 * - $hide_site_name: Toggles the visibility of the site name.
 * - $visibility: Holds the class .element-invisible or is empty.
 * - $primary_navigation: Themed Main menu.
 * - $secondary_navigation: Themed Secondary/user menu.
 * - $primary_local_tasks: Split local tasks - primary.
 * - $secondary_local_tasks: Split local tasks - secondary.
 * - $tag: Prints the wrapper element for the main content.
 * - $is_mobile: Bool, requires the Browscap module to return TRUE for mobile
 *   devices. Use to test for a mobile context.
 * - *_attributes: attributes for various site elements, usually holds id, class
 *   or role attributes.
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Core Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * Adaptivetheme Regions:
 * - $page['leaderboard']: full width at the very top of the page
 * - $page['menu_bar']: menu blocks placed here will be styled horizontal
 * - $page['secondary_content']: full width just above the main columns
 * - $page['content_aside']: like a main content bottom region
 * - $page['tertiary_content']: full width just above the footer
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 * @see adaptivetheme_preprocess_page()
 * @see adaptivetheme_process_page()
 */
if (arg(2) == 'edit')
{
	if (isset($node))
	{
		if ($node->type == 'organization')
		{
			$title = 'Update '.$node->title;
			print "<script>jQuery(document).ready(function() { initializeOrgMap(".$node->field_location['und'][0]['latitude'].", ".$node->field_location['und'][0]['longitude']."); } );</script>";
		}
		else if ($node->type == 'conference')
		{
			$conference = $node;
		}
		else if (!isset($conference) && isset($node->field_conference) && isset($node->field_conference['und']))//$node->type == 'conference_registration')
		{
			$conference = node_load($node->field_conference['und'][0]['nid']);
			//$title = $conference->title.' Registration';
		}
	}
}
else if (arg(1) == 'add')
{
	if (arg(2) == 'organization')
	{
		global $user;
		$title = 'Register an Organization';
		//print "<script>jQuery(document).ready(function() { jQuery('.field-widget-entityreference-autocomplete input.form-autocomplete').first().val('$user->name ($user->uid)').trigger('change'); } );</script>";
	}
	else if (arg(2) == 'conference-registration')
	{
		$conference = node_load(arg(3));
		$title = 'Register for '.$conference->title;
	}
	else if (arg(2) == 'workshop')
	{
		global $user;
		$conference = node_load(arg(3));
		$title = 'Propose a Workshop';
		//print "<script>jQuery(document).ready(function() { jQuery('.field-widget-entityreference-autocomplete input.form-autocomplete').first().val('$user->name ($user->uid)').trigger('change'); } );</script>";
	}
}
else if (isset($node))
{
	//if ()//$node->type == 'conference_registration')
	//{
		//$conference = node_load($node->field_conference[$node->language][0]['nid']);
		//$title = $conference->title.' Registration';
	//}
	//else
	if ($node->type == 'conference')
	{
		$conference = $node;
	}
	else if (!isset($conference) && isset($node->field_conference) && isset($node->field_conference['und']))
	{
		$conference = node_load($node->field_conference['und'][0]['nid']);
	}
}
else if (arg(0) == 'conferences' && is_numeric(arg(1)))
{
	$conference = node_load(arg(1));
}
?>
<div id="page" class="container <?php print $classes; ?>">

  <!-- region: Leaderboard -->
  <?php print render($page['leaderboard']); ?>

  <header<?php print $header_attributes; ?>>

    <?php if ($site_logo || $site_name || $site_slogan): ?>
      <!-- start: Branding -->
      <div<?php print $branding_attributes; ?>>

        <?php if ($site_logo): ?>
          <div id="logo">
            <?php print $site_logo; ?>
          </div>
        <?php endif; ?>

        <?php if ($site_name || $site_slogan): ?>
          <!-- start: Site name and Slogan hgroup -->
          <hgroup<?php print $hgroup_attributes; ?>>

            <?php if ($site_name): ?>
              <h1<?php print $site_name_attributes; ?>><?php print $site_name; ?></h1>
            <?php endif; ?>

            <?php if ($site_slogan): ?>
              <h2<?php print $site_slogan_attributes; ?>><?php print $site_slogan; ?></h2>
            <?php endif; ?>

          </hgroup><!-- /end #name-and-slogan -->
        <?php endif; ?>
        <?php global $user; if ($user && $user->uid) { $privatemsg_unread_count = privatemsg_unread_count(); print l(($privatemsg_unread_count > 0 ? '<span>'.$privatemsg_unread_count.'</span>' : ''), 'messages', array('html' => true, 'attributes' => array('id' => 'user-messages', 'title' => t('Messages'), 'class' => ($privatemsg_unread_count > 0 ? array('new') : array())))); } ?>
    </div><!-- /end #branding -->
    <?php endif; ?>

    <!-- region: Header -->
    <?php print render($page['header']); ?>

  </header>

  <!-- Navigation elements -->
  <?php print render($page['menu_bar']); ?>
  <?php if ($primary_navigation): print $primary_navigation; endif; ?>
  <?php if ($secondary_navigation): print $secondary_navigation; endif; ?>

  <!-- Breadcrumbs -->
  <?php /*<?php if ($breadcrumb): print $breadcrumb; endif; ?>*/ ?>

  <!-- region: Secondary Content -->
  <?php print render($page['secondary_content']); ?>

  <div id="columns" class="columns clearfix">
    <div id="content-column" class="content-column" role="main">
      <div class="content-inner">

      	<!-- region: Highlighted -->
        <?php print render($page['highlighted']); ?>

        <<?php print $tag; ?> id="main-content">

          <?php print render($title_prefix); // Does nothing by default in D7 core ?>

          <?php if ($title || $primary_local_tasks || $secondary_local_tasks || $action_links = render($action_links)): ?>
            <header<?php print $content_header_attributes; ?>>
				<?php if (isset($node) && $node->type == 'organization'): ?>
				<div id="org-map">
					<script>
						initializeOrgMap(<?php print $node->field_location['und'][0]['latitude'];?>, <?php print $node->field_location['und'][0]['longitude'];?>)
					</script>
				</div>
				<?php /*print theme('image_style', array('style_name' => 'square_thumbnail', 'path' => $node->field_icon[$node->language][0]['uri'], 'attributes' => array('class' => 'logo')));*/ ?>
				<?php elseif (isset($conference)): ?>
				<div id="conference-banner">
            		<?php print l(theme('image', array('style_name' => 'image', 'path' => $conference->field_banner['und'][0]['uri'], 'attributes' => array('id' => 'banner-img'))), 'node/'.$conference->nid, array('html' => true)); ?>
					<?php /*print theme('image', array('style_name' => 0, 'path' => $conference->field_banner['und'][0]['uri'], 'attributes' => array('class' => 'banner')));*/ ?>
				</div>
				<?php /*print theme('image_style', array('style_name' => 'square_thumbnail', 'path' => $node->field_icon[$node->language][0]['uri'], 'attributes' => array('class' => 'logo')));*/ ?>
				<?php endif; ?>
			  <?php /*if ($title): ?>
                <h1 id="page-title">
                  <?php print $title; ?>
                </h1>
              <?php endif;*/ ?>

          </header>
          <?php endif; ?>
          
			<!-- Messages and Help -->
			<?php print $messages; ?>
			<?php print render($page['help']); ?>

			<?php if ($title): ?>
				<div id="page-title">
			    	<h1>
			    		<?php if (isset($node) && $node && $node->type == 'organization'): ?>
			    			<?php print theme('image_style', array('style_name' => 'icon_meduim', 'path' => ($node->field_icon ? $node->field_icon['und'][0]['uri'] : variable_get('user_picture_default', '')), 'attributes' => array('class' => 'avatar'))); ?>
			    		<?php elseif (arg(0) == 'user' || arg(0) == 'users'): ?>
			    		<?php 
			    			$u = user_load(arg(1));
			    			print theme('image_style', array('style_name' => 'icon_meduim', 'path' => ($u->picture ? $u->picture->uri : variable_get('user_picture_default', '')), 'attributes' => array('class' => 'avatar')));
			    		?>
			    		<?php endif; ?>
						<?php print $title; ?>
					</h1>
				</div>
          	<?php endif; ?>
              <?php if ($primary_local_tasks || $secondary_local_tasks || $action_links): ?>
                <div id="tasks">

                  <?php if ($primary_local_tasks): ?>
                    <ul class="tabs primary clearfix"><?php print render($primary_local_tasks); ?></ul>
                  <?php endif; ?>

                  <?php if ($secondary_local_tasks): ?>
                    <ul class="tabs secondary clearfix"><?php print render($secondary_local_tasks); ?></ul>
                  <?php endif; ?>

                  <?php /*if ($action_links = render($action_links)): ?>
                    <ul class="action-links clearfix"><?php print $action_links; ?></ul>
                  <?php endif;*/ ?>

                </div>
              <?php endif; ?>
          	
			<!-- region: Main Content -->
          <?php if ($content = render($page['content'])): ?>
            <div id="content" class="region">
				<?php /*print render($content);$page['content']);*/ ?>
            <?php /*if (arg(2) != 'edit' && isset($node) && $node->type == 'conference_registration'): ?>
            		<?php if ($node->field_attending_conference[$node->language][0]['value'] == 1): ?>
            		<a href="/conference-registration/<?php print $node->nid; ?>/cancel" class="important-button" title="Mark this registration as Attending: No (you can undo this)">Cancel my registration</a>
            		<?php elseif ($node->field_attending_conference[$node->language][0]['value'] == 0): ?>
            		<a href="/conference-registration/<?php print $node->nid; ?>/confirm" class="important-button" title="Mark this registration as Attending: Yes (you can undo this)">Confirm my registration</a>
            		<?php else: ?>
            		<a href="/conference-registration/<?php print $node->nid; ?>/confirm" class="important-button" title="Mark this registration as Attending: Yes (you can undo this)">Reverse my Cancellation</a>
            		<?php endif; */?>
              <?php print $content; ?>
             </div>
          <?php endif; ?>
             
          <!-- Feed icons (RSS, Atom icons etc -->
          <?php print $feed_icons; ?>

          <?php print render($title_suffix); // Prints page level contextual links ?>

        </<?php print $tag; ?>><!-- /end #main-content -->

        <!-- region: Content Aside -->
        <?php print render($page['content_aside']); ?>

      </div><!-- /end .content-inner -->
    </div><!-- /end #content-column -->

    <!-- regions: Sidebar first and Sidebar second -->
    <?php $sidebar_first = render($page['sidebar_first']); print $sidebar_first; ?>
    <?php $sidebar_second = render($page['sidebar_second']); print $sidebar_second; ?>

  </div><!-- /end #columns -->

  <!-- region: Tertiary Content -->
  <?php print render($page['tertiary_content']); ?>

  <!-- region: Footer -->
  <?php if ($page['footer']): ?>
    <footer<?php print $footer_attributes; ?>>
      <?php print render($page['footer']); ?>
    </footer>
  <?php endif; ?>

</div>