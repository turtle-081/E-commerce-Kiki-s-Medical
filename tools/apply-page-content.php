<?php
/**
 * Replace the demo page content with the client's real content, create the
 * policy pages the footer links to, and delete the leftover template pages.
 *
 *   php tools/apply-page-content.php          # dry run
 *   php tools/apply-page-content.php apply    # write
 *
 * Copy is adapted from the client's live site at kikismedsupplies.com. Two
 * details there conflict with what the client gave us directly, and the direct
 * instruction wins -- see the notes printed at the end.
 *
 * Content is written as plain semantic HTML rather than WPBakery shortcodes.
 * The demo pages were shortcode layouts, but those are brittle to generate and
 * impossible to review; HTML renders through the_content just as well and can be
 * edited by hand later.
 *
 * TAKE A DATABASE BACKUP FIRST.
 */

$apply = ( ( $argv[1] ?? '' ) === 'apply' );
$port  = isset( $argv[2] ) ? (int) $argv[2] : 10004;

$root   = dirname( __DIR__ );
$config = @file_get_contents( $root . '/app/public/wp-config.php' );
if ( ! $config ) {
	fwrite( STDERR, "cannot read wp-config.php\n" );
	exit( 1 );
}
$conf = array();
foreach ( array( 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_HOST' ) as $k ) {
	preg_match( "/define\(\s*'$k'\s*,\s*'([^']*)'/", $config, $m );
	$conf[ $k ] = $m[1] ?? '';
}
$host = preg_replace( '/:\d+$/', '', $conf['DB_HOST'] );
if ( 'localhost' === $host ) {
	$host = '127.0.0.1';
}

$db = mysqli_init();
$db->options( MYSQLI_OPT_CONNECT_TIMEOUT, 5 );
if ( ! @$db->real_connect( $host, $conf['DB_USER'], $conf['DB_PASSWORD'], $conf['DB_NAME'], $port ) ) {
	fwrite( STDERR, 'connect failed: ' . mysqli_connect_error() . "\n" );
	exit( 1 );
}
$db->set_charset( 'utf8mb4' );

$ADDRESS = 'London Beauty Building, 4th Floor, Suite A6<br />Taveta Road, Nairobi CBD, Kenya';
$MAP     = 'https://maps.app.goo.gl/BfGpndo2C43aCZDe7';
$HOURS   = 'Monday &ndash; Friday: 8:00 &ndash; 18:00<br />Saturday: 8:00 &ndash; 16:00';

/* ------------------------------------------------------------------ content */

$ABOUT = <<<HTML
<h2>Medical apparel, equipment and consumables &mdash; made and supplied in Kenya</h2>
<p>Kiki's Medical Supplies designs, produces and supplies the apparel, equipment and everyday essentials that Kenya's healthcare workers rely on &mdash; held to a standard the people who care for us deserve.</p>

<h3>Apparel, equipment &amp; consumables</h3>
<p>We bring together everything a healthcare setting needs: medical apparel like scrubs, nurse uniforms and theatre wear; trusted equipment like stethoscopes and blood-pressure monitors; and the everyday consumables &mdash; gloves, dressings and first-aid kits &mdash; that keep a clinic running.</p>

<h3>Made locally</h3>
<p>We design and produce our medical wear locally, keeping the craft and the jobs here at home. Our garments are made to be comfortable in the heat, durable through everyday laundering, and dignified for the people who wear them.</p>

<h3>Built around your needs</h3>
<p>Because we are hands-on with what we make and supply, we can work to your requirements &mdash; including custom embroidery and branding for clinics, teams and institutions.</p>
<p>From a single clinic to a county-wide tender, we offer volume pricing and custom embroidery on request. Alongside scrubs we make nurse uniforms, lab coats, theatre wear and caps, as well as branded staff uniforms and executive wear for reception and administrative teams.</p>

<h3>Visit us</h3>
<p>$ADDRESS<br /><a href="$MAP">Show on map</a></p>
<p>$HOURS</p>
<p>Telephone <a href="tel:+254704329920">+254&nbsp;704&nbsp;329&nbsp;920</a> or <a href="tel:+254740928234">+254&nbsp;740&nbsp;928&nbsp;234</a><br />Email <a href="mailto:info@kikismedsupplies.com">info@kikismedsupplies.com</a></p>
HTML;

$CONTACT = <<<HTML
<h2>We'd love to hear from you</h2>
<p>Whether you are outfitting a single clinic or a county-wide tender, our trade team will build a quote around your needs.</p>

<h3>Visit the shop</h3>
<p>$ADDRESS<br /><a href="$MAP">Show on map</a></p>
<p>$HOURS</p>

<h3>Talk to us</h3>
<ul>
<li>Telephone or WhatsApp: <a href="tel:+254704329920">+254&nbsp;704&nbsp;329&nbsp;920</a> &middot; <a href="tel:+254740928234">+254&nbsp;740&nbsp;928&nbsp;234</a></li>
<li>General enquiries: <a href="mailto:info@kikismedsupplies.com">info@kikismedsupplies.com</a></li>
<li>Trade, bulk and tenders: <a href="mailto:sales@kikismedsupplies.com">sales@kikismedsupplies.com</a></li>
<li>Returns: <a href="mailto:returns@kikismedsupplies.com">returns@kikismedsupplies.com</a></li>
</ul>

<h3>What can we help with?</h3>
<ul>
<li>Apparel orders &mdash; scrubs, nurse uniforms, lab coats, theatre wear</li>
<li>Equipment and devices</li>
<li>Consumables and bulk supply</li>
<li>Dental supplies</li>
<li>County or hospital tenders</li>
<li>Custom embroidery and branding</li>
</ul>
<p>Can't find a product on the site? Call or WhatsApp us and we will source it for you.</p>
HTML;

$FAQ = <<<HTML
<h2>Frequently asked questions</h2>
<p>Can't find your answer? Reach us any time on <a href="tel:+254740928234">+254&nbsp;740&nbsp;928&nbsp;234</a>.</p>

<h3>Do you have a physical store?</h3>
<p>Yes. Visit us at $ADDRESS &mdash; <a href="$MAP">show on map</a>.<br />$HOURS</p>

<h3>Can I order a product that isn't on the website?</h3>
<p>Absolutely. Call or WhatsApp us on <a href="tel:+254740928234">+254&nbsp;740&nbsp;928&nbsp;234</a>, email <a href="mailto:sales@kikismedsupplies.com">sales@kikismedsupplies.com</a>, or drop by the store and we'll source it for you.</p>

<h3>Do you make attire other than medical scrubs?</h3>
<p>Yes. Alongside scrubs we make nurse uniforms, lab coats, theatre wear and caps, as well as branded staff uniforms (polo shirts and t-shirts) and executive suits for employees and receptionists.</p>

<h3>Can I get custom or branded apparel?</h3>
<p>We offer custom embroidery and branding &mdash; ideal for clinics, teams and institutions. Tell us what you need via the contact page.</p>

<h3>How can I pay?</h3>
<p>M-Pesa (an STK prompt to your phone), card or mobile money via Pesapal, or pay on delivery within the Nairobi metro. Institutions can also be invoiced on account.</p>

<h3>Are your prices VAT-inclusive?</h3>
<p>Prices shown are exclusive of VAT. VAT (currently 16%) is calculated and added at checkout, along with any delivery fee, so you always see the full total before paying.</p>

<h3>Do you deliver across Kenya and internationally?</h3>
<p>Yes &mdash; countrywide via trusted couriers, and worldwide by quote. See our delivery information for zones, rates and timelines.</p>

<h3>What is your returns policy?</h3>
<p>We accept returns under the terms in our returns and refunds policy. Faulty or wrongly supplied items are always put right at our cost.</p>

<h3>Do you supply hospitals and institutions?</h3>
<p>Yes &mdash; bulk and tender supply is a core part of what we do. Contact our trade team on <a href="mailto:sales@kikismedsupplies.com">sales@kikismedsupplies.com</a> for a quote.</p>
HTML;

$DELIVERY = <<<HTML
<h2>Shipping &amp; delivery</h2>
<p>We deliver quality medical supplies to customers across Kenya and worldwide from our base in Nairobi. Here is where we deliver, what it costs, and how long it takes.</p>
<p><strong>Please note.</strong> This policy reflects our current practice. We recommend a final review by a qualified lawyer before it is relied upon, especially for international sales.</p>

<h3>Collection from our shop</h3>
<p>You are welcome to collect your order from $ADDRESS &mdash; <a href="$MAP">show on map</a>.<br />$HOURS</p>
<p>CBD drop-off is also available from KSh&nbsp;100.</p>

<h3>Nairobi delivery</h3>
<p>Within Nairobi, delivery is priced by distance from the CBD. The exact fee for your area is shown at checkout. Typical rates:</p>
<table>
<tr><th>Area (examples)</th><th>From (KSh)</th></tr>
<tr><td>CBD drop-off / pickup</td><td>100</td></tr>
<tr><td>Upperhill &middot; Chiromo &middot; City Stadium</td><td>250</td></tr>
<tr><td>Muthaiga &middot; Westlands &middot; South B/C &middot; Karura &middot; Kibera</td><td>300</td></tr>
<tr><td>Ridgeways &middot; Junction &middot; Garden City &middot; Outering &middot; Allsops &middot; EABL</td><td>400&ndash;450</td></tr>
<tr><td>Kangemi &middot; Imara</td><td>500</td></tr>
<tr><td>Loresho &middot; Uthiru</td><td>600</td></tr>
<tr><td>Pipeline &middot; Kabanas</td><td>700</td></tr>
</table>
<p>Outer zones are charged by range: 12&ndash;15&nbsp;km from KSh&nbsp;900, 15&ndash;18&nbsp;km from KSh&nbsp;1,000, and 18&nbsp;km and beyond from KSh&nbsp;1,200. Rates shown are for bus and public couriers; for a specific private courier, please call us.</p>

<h3>Countrywide delivery</h3>
<p>Deliveries to Central, Rift Valley, Western, Coastal, Nyanza and North-Eastern regions are from KSh&nbsp;300 via public courier, applied after checkout. For a preferred private courier, give us a call and we will arrange it.</p>

<h3>International delivery</h3>
<p>We ship worldwide to reach healthcare professionals, institutions and individuals wherever they are. International rates depend on destination, weight and carrier, and are quoted before dispatch. Email <a href="mailto:sales@kikismedsupplies.com">sales@kikismedsupplies.com</a> with your items and delivery country for a quote. Any import duties or taxes charged by the destination country are the customer's responsibility.</p>

<h3>Tracking your order</h3>
<p>After checkout you receive an order reference and a live status page. We confirm dispatch by call, SMS or WhatsApp, and pass on a courier tracking number where one is provided. Made-to-order and embroidered items add production time before dispatch.</p>
<p>Risk passes to you on delivery. Please inspect your order on arrival and report any damage or shortage within 48 hours so we can put it right.</p>
HTML;

$RETURNS = <<<HTML
<h2>Returns &amp; refunds</h2>
<p>Kiki's Medical Equipment and Hospital Supplies Limited wants you to be completely satisfied with your purchase. If for any reason you are not, we will gladly accept returns for exchange under the terms below.</p>
<p><strong>Please note.</strong> This policy reflects our current practice. We recommend a final review by a qualified lawyer before it is relied upon, especially for international sales.</p>

<h3>1. Condition of returned items</h3>
<p>Unless an item is faulty or was damaged on arrival, items must be returned in new, unused, saleable condition with their original tags and packaging. Once we receive an item, our team inspects it before processing the return. Items returned used or damaged, or outside the windows below, may not be eligible for a refund or credit.</p>

<h3>2. Return windows</h3>
<ul>
<li><strong>In store</strong> (our Nairobi shop): within 24 hours of purchase, with a valid receipt or proof of purchase.</li>
<li><strong>Online, within Kenya:</strong> within 3 days of the delivery date.</li>
<li><strong>Online, international:</strong> see "Your statutory rights" below &mdash; you may have a longer cooling-off period.</li>
</ul>

<h3>3. Refunds and credit</h3>
<p>For approved change-of-mind returns within Kenya, refunds are issued as store credit, which can be used towards another product of similar value. Any shipping and handling fees may be deducted unless the return is our fault.</p>
<p>Faulty, damaged or wrongly supplied items are refunded to your original payment method (M-Pesa to the paying number, or card and mobile money via Pesapal) or replaced &mdash; your choice.</p>

<h3>4. Items that can't be returned</h3>
<p>All items are eligible for return except:</p>
<ul>
<li>clearance and final-sale items;</li>
<li>special-order and custom or embroidered items;</li>
<li>hygiene-sealed consumables such as opened gloves, dressings or first-aid contents &mdash; returnable only if unopened or faulty.</li>
</ul>
<p>We may decline a return that does not meet these criteria, at the discretion of store management.</p>

<h3>5. How to start a return</h3>
<p>Contact us at <a href="mailto:returns@kikismedsupplies.com">returns@kikismedsupplies.com</a> or WhatsApp <a href="tel:+254740928234">+254&nbsp;740&nbsp;928&nbsp;234</a> to request a return authorisation. Include your order number and the reason for return. Returns shipped without authorisation may not be accepted. Please allow up to 10 business days for your refund or credit to be processed once we receive the item.</p>

<h3>6. Return shipping</h3>
<p>For items that are faulty, damaged or sent in error, we cover return shipping and will provide a pre-paid label or arrange collection. For change-of-mind returns, return shipping is paid by the customer.</p>

<h3>7. Your statutory rights</h3>
<p>Nothing in this policy limits rights you have under the law that applies to you. If you are a consumer buying online from the EU or UK, you generally have the right to cancel your order within 14 days of receiving it and to receive a refund to your original payment method &mdash; not only store credit &mdash; subject to the goods being returned in line with those laws. Faulty-goods rights under the Kenya Consumer Protection Act, 2012 and equivalent laws elsewhere also always apply.</p>
<p>If your local law gives you stronger rights than this policy, the law prevails. Contact us and we will make sure you get what you are entitled to.</p>
HTML;

$TERMS = <<<HTML
<h2>Terms &amp; conditions</h2>
<p><strong>This page is a placeholder.</strong> Terms of sale are a legal document specific to Kiki's Medical Equipment and Hospital Supplies Limited and should be drafted or reviewed by a qualified lawyer before the site goes live. The outline below reflects current practice and is a starting point only.</p>

<h3>Who we are</h3>
<p>Kiki's Medical Equipment and Hospital Supplies Limited, registration PVT-Y2ULB55R, of $ADDRESS.</p>

<h3>Prices and VAT</h3>
<p>Prices shown are exclusive of VAT. VAT (currently 16%) and any delivery fee are calculated and added at checkout, so the full total is shown before payment.</p>

<h3>Payment</h3>
<p>We accept M-Pesa, card and mobile money via Pesapal, and pay on delivery within the Nairobi metro. Institutions may be invoiced on account by prior arrangement.</p>

<h3>Delivery</h3>
<p>Delivery zones, rates and timelines are set out in our delivery information. Risk passes to the customer on delivery.</p>

<h3>Returns</h3>
<p>Returns and refunds are governed by our returns and refunds policy.</p>

<h3>Bulk and tender supply</h3>
<p>Supply to hospitals, counties and institutions may be governed by a separate supply agreement. Contact <a href="mailto:sales@kikismedsupplies.com">sales@kikismedsupplies.com</a>.</p>
HTML;

/* ------------------------------------------------------------------- update */

$REWRITE = array(
	355 => array( 'About us', $ABOUT ),
	357 => array( 'Contact us', $CONTACT ),
	365 => array( 'FAQ', $FAQ ),
);

echo "1. rewrite existing pages\n";
$stmt = $db->prepare( 'UPDATE wp_posts SET post_content=? WHERE ID=?' );
foreach ( $REWRITE as $id => $spec ) {
	list( $title, $html ) = $spec;
	$r = $db->query( "SELECT post_title, LENGTH(post_content) len FROM wp_posts WHERE ID=$id" );
	if ( ! $r->num_rows ) {
		echo "  $id ($title): page missing, skipped\n";
		continue;
	}
	$cur = $r->fetch_assoc();
	printf( "  %-5s %-12s %6s chars -> %s chars\n", $id, $cur['post_title'], $cur['len'], strlen( $html ) );
	if ( $apply ) {
		$stmt->bind_param( 'si', $html, $id );
		$stmt->execute();
		// The demo pages carry WPBakery layout metadata that no longer applies.
		$db->query( "DELETE FROM wp_postmeta WHERE post_id=$id AND meta_key IN ('_wpb_shortcodes_custom_css','_wpb_vc_js_status')" );
	}
}

/* ------------------------------------------------------------------- create */

$CREATE = array(
	array( 'Delivery information', 'delivery-information', $DELIVERY, 'Delivery information' ),
	array( 'Returns &amp; refunds', 'returns-refunds', $RETURNS, 'Returns' ),
	array( 'Terms &amp; conditions', 'terms-conditions', $TERMS, 'Terms &amp; Conditions' ),
);

echo "\n2. create the pages the footer links to\n";
$created = array();
foreach ( $CREATE as $spec ) {
	list( $title, $slug, $html, $menuLabel ) = $spec;
	$existing = $db->query( "SELECT ID FROM wp_posts WHERE post_type='page' AND post_name='$slug' LIMIT 1" );
	if ( $existing->num_rows ) {
		$id = (int) $existing->fetch_row()[0];
		echo "  $slug: already exists (id $id), content refreshed\n";
		if ( $apply ) {
			$stmt->bind_param( 'si', $html, $id );
			$stmt->execute();
		}
		$created[ $menuLabel ] = $id;
		continue;
	}
	echo "  $slug: create (" . strlen( $html ) . " chars)\n";
	if ( $apply ) {
		$t = $db->real_escape_string( $title );
		$s = $db->real_escape_string( $slug );
		$c = $db->real_escape_string( $html );
		$db->query(
			"INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt,
			 post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged,
			 post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type)
			 VALUES (1, NOW(), UTC_TIMESTAMP(), '$c', '$t', '', 'publish', 'closed', 'closed', '', '$s', '', '',
			 NOW(), UTC_TIMESTAMP(), '', 0, '', 0, 'page', '')"
		);
		$id = $db->insert_id;
		$db->query( "UPDATE wp_posts SET guid='http://client1.local/?page_id=$id' WHERE ID=$id" );
		$created[ $menuLabel ] = $id;
		echo "    created id $id\n";
	}
}

/* -------------------------------------------------------------- menu wiring */

echo "\n3. point the footer menu items at the new pages\n";
if ( $apply && $created ) {
	foreach ( $created as $label => $pageId ) {
		$like = $db->real_escape_string( $label );
		$res  = $db->query(
			"SELECT p.ID FROM wp_posts p
			 JOIN wp_postmeta pm ON pm.post_id=p.ID AND pm.meta_key='_menu_item_url' AND pm.meta_value='#'
			 WHERE p.post_type='nav_menu_item' AND p.post_title='$like'"
		);
		$n = 0;
		while ( $row = $res->fetch_row() ) {
			$mid = (int) $row[0];
			$db->query( "UPDATE wp_postmeta SET meta_value='post_type' WHERE post_id=$mid AND meta_key='_menu_item_type'" );
			$db->query( "UPDATE wp_postmeta SET meta_value='page' WHERE post_id=$mid AND meta_key='_menu_item_object'" );
			$db->query( "UPDATE wp_postmeta SET meta_value='$pageId' WHERE post_id=$mid AND meta_key='_menu_item_object_id'" );
			$db->query( "UPDATE wp_postmeta SET meta_value='' WHERE post_id=$mid AND meta_key='_menu_item_url'" );
			$n++;
		}
		echo "  $label -> page $pageId ($n menu item(s))\n";
	}
} else {
	echo "  (runs on apply)\n";
}

/* ------------------------------------------------------------------- delete */

$DELETE_SLUGS = array( 'elements', 'features', 'ui-elements', 'career', 'contact-us-2', 'contact-us-3' );

echo "\n4. delete leftover demo pages\n";
$in  = "'" . implode( "','", $DELETE_SLUGS ) . "'";
$res = $db->query( "SELECT ID, post_title, post_name FROM wp_posts WHERE post_type='page' AND post_name IN ($in)" );
$ids = array();
while ( $row = $res->fetch_assoc() ) {
	$refs = $db->query( "SELECT COUNT(*) FROM wp_postmeta WHERE meta_key='_menu_item_object_id' AND meta_value='{$row['ID']}'" )->fetch_row()[0];
	printf( "  %-5s %-16s /%s/  (menu refs: %s)\n", $row['ID'], $row['post_title'], $row['post_name'], $refs );
	$ids[] = (int) $row['ID'];
}
// Any menu item pointing at a deleted page has to go with it, or the footer
// keeps rendering a link to a page that no longer exists.
$orphans = array();
if ( $ids ) {
	$list = implode( ',', $ids );
	$res  = $db->query(
		"SELECT p.ID, p.post_title, pm.meta_value AS objid
		 FROM wp_posts p
		 JOIN wp_postmeta pm ON pm.post_id = p.ID AND pm.meta_key='_menu_item_object_id'
		 WHERE p.post_type='nav_menu_item' AND pm.meta_value IN ($list)"
	);
	while ( $row = $res->fetch_assoc() ) {
		$orphans[ (int) $row['ID'] ] = $row['post_title'] . ' -> page ' . $row['objid'];
	}
}
foreach ( $orphans as $mid => $what ) {
	echo "  menu item $mid would break: $what\n";
}
printf( "  %d menu item(s) to remove alongside\n", count( $orphans ) );

if ( $apply && $ids ) {
	$list = implode( ',', $ids );
	if ( $orphans ) {
		$mlist = implode( ',', array_map( 'intval', array_keys( $orphans ) ) );
		$db->query( "DELETE FROM wp_postmeta WHERE post_id IN ($mlist)" );
		$db->query( "DELETE FROM wp_term_relationships WHERE object_id IN ($mlist)" );
		$db->query( "DELETE FROM wp_posts WHERE ID IN ($mlist)" );
		echo '  removed ' . count( $orphans ) . " menu item(s)\n";
	}
	$db->query( "DELETE FROM wp_postmeta WHERE post_id IN ($list)" );
	$db->query( "DELETE FROM wp_posts WHERE ID IN ($list) OR (post_parent IN ($list) AND post_type='revision')" );
	echo '  deleted ' . count( $ids ) . " page(s)\n";
}

if ( $apply ) {
	$db->query( "DELETE FROM wp_options WHERE option_name LIKE '_transient_enovathemes-%' OR option_name LIKE '_transient_timeout_enovathemes-%'" );
	printf( "\ncleared %d cache row(s)\n", $db->affected_rows );
	echo "done\n";
} else {
	echo "\nDRY RUN - nothing written. Pass 'apply' to write.\n";
}

echo "\nNOTES\n";
echo "  Opening hours: the live site says Mon-Fri 8:00-17:00 and Sat 9:00-13:00.\n";
echo "  The client gave us Mon-Fri 8:00-18:00 and Sat 8:00-16:00 directly, so that\n";
echo "  is what is used here and in the footer. Worth confirming which is correct.\n";
echo "  Email addresses: info@ for general, sales@ for trade, returns@ for returns,\n";
echo "  matching the live site's usage.\n";
