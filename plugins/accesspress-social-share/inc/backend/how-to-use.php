<?php defined('ABSPATH') or die("No script kiddies please!"); ?>
<p>Basically there are four main settings tabs that will help you to setup the plugin to work properly.</p>
<dl>
    <dt><strong>Social Networks</strong></dt>
    <dd><p>In this tab you can choose which social media icons you want to display in the frontend. Here you can order the apperance of the social icons simply by drag and drop of each social icons.</p></dd>

    <dt><strong>Share options</strong></dt>
    <dd><p>In this tab you can select the options where you want to display social media share icons.</p></dd>

    <dt><strong>Display settings</strong></dt>
    <dd><p>In this tab you can customize the apperance of the social media share icons. You can choose the options to display the share icons below the contennt, above the content or you can choose an options to display in both below and above content. Also you can choose the theme from the pre available 5 themes.</p></dd>

    <dt><strong>Miscellaneous settings</strong></dt>
    <dd><p>In this tab you can do the additional settings for the plugin.
    <ul class="how-list">
        <li><i class="fa fa-check"></i>You can setup the twitter username.</li>
        <li><i class="fa fa-check"></i>You can enable/disable the social counter.</li>
        <li><i class="fa fa-check"></i>Share link - You can enable the share options either in new tab/window or in same widow.</li>
        <li><i class="fa fa-check"></i>Cache settings - Here you can set the cache settings of the social share counter in hour format.</li>
        <li><i class="fa fa-check"></i>Email settings - If you have enabled the email settings you can setup the email header and body part.</li>
    </ul>
    </p></dd>
    <dt><strong>Shortcode</strong></dt>
    <dd><p>You can use shortcode for the display of the social share in the contents. Optionally You can enter the name of the networks you want to display. The networks will be displayed in the order of entered networks.
    <ul class="how-list">
        <li><i class="fa fa-check"></i>Example 1: <code>[apss_share]</code></li>
        <li>Available shortcode parameters</li>
        <ul>
            <li><i class="fa fa-check"></i>networks : You can define which social medias to show in the shortcode. You need to enter the networks name in string in comma separated values. If you don't want to choose which social medias to appear in shortcode, you can discard this option. </li>
            <li>Available network parameters are: facebook, twitter, google-plus, pinterest, linkedin, digg, email, print</li>
            <li><i class="fa fa-check"></i>share_text: You can add the share text. To use share text use share_text='text to be shared'. If you don't use this parameter the share text will not appear in shortcode.</li>
            <li><i class="fa fa-check"></i>counter : You can enable or disable the share counter. To enable the share count use counter='1' and to disable it simply don't use counter parameter or use parameter counter='0'.</li>
            <li><i class="fa fa-check"></i>total_counter : You can enable or disable the total share counter. To enable the total share count use total_counter='1' and to disable it simply don't use total_counter parameter or use parameter total_counter='0'.</li>
            <li><i class="fa fa-check"></i>custom_share_link : You can enter the custom share link in case if the link provided by shortcode is not as per your need. To enable the custom share link use custom_share_link='custom link as per your need.'</li>
        </ul>
        <li><i class="fa fa-check"></i>Example 1.1: <code>[apss_share networks='facebook, twitter, pinterest' share_text='Share it' counter='1' total_counter='1']</code></li>
    </ul>
    </p></dd>
    <dd>
    <p>You can use the below shortcode to display the share counts number only in the content using shortcode. You can wrap that number in your reqired html tags and use it as per your need. The share count displayed will be the sum of entered network attributes.
    <ul class="how-list">
        <li><li><i class="fa fa-check"></i>Example 2: <code>[apss_count]</code></li></li>
        <li>Available Parameters
            <ul>
                <li><i class="fa fa-check"></i>network : You can define which social medias to show total share counts. You need to enter the networks name in string in comma separated values. You need to enter at least one network attribute.</li>
                <li>Available network parameters are: facebook, twitter, google-plus, pinterest, linkedin</li>
                <li>Example 2.1: <code>[apss_count network='facebook, pinterest']</code></li>
                <li>This will show the sum of share counts from facebook and pinterest.</li>
            </ul>
            <ul>
                <li><i class="fa fa-check"></i>custom_url_link</li>
                <li>Now you can use attribute "custom_url_link" to fetch the share counts for custom url as well.</li>
                <li>Example 2.2: <code>[apss_count network='facebook, pinterest' custom_url_link='<?php echo site_url('sample-page'); ?>']</code></li>
                <li>This attribute is useful in case if the shortcode is not fetching the share counts for shortcode placed url and force the shortcode to use the url from the attribute defined in  custom_url_link.</li>
            </ul>
        </li>
    </ul>
    </p>
    </dd>
    <dd>
    <p><b>For now you can use shortcode [apss-share] and [apss-count] for the display of the social shares and counts only as well but in upcoming plugin updates this shortcode will be removed and use the new one. So we suggest to use only the new shortcodes.</b></p>
    </dd>
</dl>