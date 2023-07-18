<div class="wrap pmpro_admin pmpro_admin-pmpro-membershiplevels">
<form action="" method="post" enctype="multipart/form-data">
    <input name="saveid" type="hidden" value="-1">
    <input type="hidden" name="action" value="save_membershiplevel">
    <input type="hidden" id="pmpro_membershiplevels_nonce" name="pmpro_membershiplevels_nonce" value="e35e909e76"><input
        type="hidden" name="_wp_http_referer"
        value="/wp-admin/admin.php?page=pmpro-membershiplevels&amp;edit=-1&amp;template=annual">
    <div id="general-information" class="pmpro_section" data-visibility="shown" data-activated="true">
        <div class="pmpro_section_toggle">
            <button class="pmpro_section-toggle-button" type="button" aria-expanded="true">
                <span class="dashicons dashicons-arrow-up-alt2"></span>
                General Information </button>
        </div>
        <div class="pmpro_section_inside">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row" valign="top"><label for="name">Name</label></th>
                        <td><input name="name" type="text" value="" class="regular-text" required=""></td>
                    </tr>
                    <tr>
                        <th scope="row" valign="top"><label for="description">Description</label></th>
                        <td class="pmpro_description">
                            <div id="wp-description-wrap" class="wp-core-ui wp-editor-wrap html-active">
                                <link rel="stylesheet" id="editor-buttons-css"
                                    href="http://pardzworld2.com/wp-includes/css/editor.min.css?ver=6.2" media="all">
                                <div id="wp-description-editor-container" class="wp-editor-container">
                                    <div id="qt_description_toolbar" class="quicktags-toolbar hide-if-no-js"><textarea class="wp-editor-area" rows="5"
                                        autocomplete="off" cols="40" name="description" id="description"></textarea>
                                </div>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <th scope="row" valign="top"><label for="confirmation">Confirmation Message</label></th>
                        <td class="pmpro_confirmation">
                            <div id="wp-confirmation-wrap" class="wp-core-ui wp-editor-wrap html-active">
                                <div id="wp-confirmation-editor-container" class="wp-editor-container">
                                    <div id="qt_confirmation_toolbar" class="quicktags-toolbar hide-if-no-js"><textarea
                                        class="wp-editor-area" rows="5" autocomplete="off" cols="40" name="confirmation"
                                        id="confirmation"></textarea>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- end pmpro_section_inside -->
    </div> <!-- end pmpro_section -->


    <div id="billing-details" class="pmpro_section" data-visibility="shown" data-activated="true">
        <div class="pmpro_section_toggle">
            <button class="pmpro_section-toggle-button" type="button" aria-expanded="true">
                <span class="dashicons dashicons-arrow-up-alt2"></span>
                Billing Details </button>
        </div>
        <div class="pmpro_section_inside">
            <p>Set the member pricing for this level. The initial payment is collected immediately at checkout.
                Recurring payments, if applicable, begin one cycle after the initial payment. Changing the level price
                only applies to new members and does not affect existing members of this level.</p>
            <p>Optional: Allow more customizable trial periods and renewal dates using the <a
                    href="https://www.paidmembershipspro.com/add-ons/subscription-delays/?utm_source=plugin&amp;utm_medium=pmpro-membershiplevels&amp;utm_campaign=add-ons&amp;utm_content=subscription-delays"
                    title="Paid Memberships Pro - Subscription Delays Add On" target="_blank">Subscription Delays Add
                    On</a>.</p>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row" valign="top"><label for="initial_payment">Initial Payment</label></th>
                        <td>
                            INR <input name="initial_payment" type="text" value="100" class="regular-text">
                            <p class="description">The initial amount collected at registration.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" valign="top"><label>Recurring Subscription</label></th>
                        <td><input id="recurring" name="recurring" type="checkbox" value="yes" checked="checked"
                                onclick="if(jQuery('#recurring').is(':checked')) { jQuery('.recurring_info').show(); if(jQuery('#custom_trial').is(':checked')) {jQuery('.trial_info').show();} else {jQuery('.trial_info').hide();} } else { jQuery('.recurring_info').hide();}">
                            <label for="recurring">Check if this level has a recurring subscription payment.</label>
                        </td>
                    </tr>

                    <tr class="recurring_info">
                        <th scope="row" valign="top"><label for="billing_amount">Billing Amount</label></th>
                        <td>
                            INR <input name="billing_amount" type="text" value="100" class="regular-text">
                            per <input id="cycle_number" name="cycle_number" type="text" value="1" class="small-text">
                            <select id="cycle_period" name="cycle_period">
                                <option value="Day">Day(s)</option>
                                <option value="Week">Week(s)</option>
                                <option value="Month">Month(s)</option>
                                <option value="Year" selected="selected">Year(s)</option>
                            </select>
                            <p class="description">
                                The amount to be billed one cycle after the initial payment. </p>
                        </td>
                    </tr>
                    <tr class="recurring_info">
                        <th scope="row" valign="top"><label for="billing_limit">Billing Cycle Limit</label></th>
                        <td>
                            <input name="billing_limit" type="text" value="" class="small-text">
                            <p class="description">
                                The <strong>total</strong> number of recurring billing cycles for this level, including
                                the trial period (if applicable) but not including the initial payment. Set to zero if
                                membership is indefinite. </p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="form-table">
                <tbody>
                    <tr class="recurring_info">
                        <th scope="row" valign="top"><label>Custom Trial</label></th>
                        <td>
                            <input id="custom_trial" name="custom_trial" type="checkbox" value="yes"
                                onclick="jQuery('.trial_info').toggle();"> <label for="custom_trial">Check to add a
                                custom trial period.</label>

                        </td>
                    </tr>
                    <tr class="trial_info recurring_info" style="display:none;">
                        <th scope="row" valign="top"><label for="trial_amount">Trial Billing Amount</label></th>
                        <td>
                            INR <input name="trial_amount" type="text" value="0" class="regular-text">
                            for the first <input name="trial_limit" type="text" value="" class="small-text">
                            subscription payments.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- end pmpro_section_inside -->
    </div> <!-- end pmpro_section -->

    <div id="expiration-details" class="pmpro_section" data-visibility="shown" data-activated="false">
        <div class="pmpro_section_toggle">
            <button class="pmpro_section-toggle-button" type="button" aria-expanded="true">
                <span class="dashicons dashicons-arrow-up-alt2"></span>
                Expiration Settings </button>
        </div>
        <div class="pmpro_section_inside" style="">
            <div id="pmpro_expiration_warning" style="display: none;" class="notice notice-alt notice-error inline">
                <p>WARNING: This level is set with both a recurring billing amount and an expiration date. You only need
                    to set one of these unless you really want this membership to expire after a certain number of
                    payments. For more information, <a target="_blank" rel="nofollow noopener"
                        href="https://www.paidmembershipspro.com/important-notes-on-recurring-billing-and-expiration-dates-for-membership-levels/?utm_source=plugin&amp;utm_medium=pmpro-membershiplevels&amp;utm_campaign=blog&amp;utm_content=important-notes-on-recurring-billing-and-expiration-dates-for-membership-levels">see
                        our post here</a>.</p>
            </div>
            <script>
                jQuery(document).ready(function () {
                    function pmpro_expirationWarningCheck() {
                        if (jQuery('#recurring:checked').length && jQuery('#expiration:checked').length) {
                            jQuery('#pmpro_expiration_warning').show();
                        } else {
                            jQuery('#pmpro_expiration_warning').hide();
                        }
                    }

                    pmpro_expirationWarningCheck();

                    jQuery('#recurring,#expiration').change(function () { pmpro_expirationWarningCheck(); });
                });
            </script>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row" valign="top"><label>Membership Expiration</label></th>
                        <td><input id="expiration" name="expiration" type="checkbox" value="yes"
                                onclick="if(jQuery('#expiration').is(':checked')) { jQuery('.expiration_info').show(); } else { jQuery('.expiration_info').hide();}">
                            <label for="expiration">Check this to set when membership access expires.</label></td>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <td>
                            <p class="description">Optional: Allow more customizable expiration dates using the <a
                                    href="https://www.paidmembershipspro.com/add-ons/pmpro-expiration-date/?utm_source=plugin&amp;utm_medium=pmpro-membershiplevels&amp;utm_campaign=add-ons&amp;utm_content=pmpro-expiration-date"
                                    title="Paid Memberships Pro - Set Expiration Date Add On" target="_blank">Set
                                    Expiration Date Add On</a>.</p>
                        </td>
                    </tr>
                    <tr class="expiration_info" style="display: none;">
                        <th scope="row" valign="top"><label for="billing_amount">Expires In</label></th>
                        <td>
                            <input id="expiration_number" name="expiration_number" type="text" value=""
                                class="small-text">
                            <select id="expiration_period" name="expiration_period">
                                <option value="Hour">Hour(s)</option>
                                <option value="Day">Day(s)</option>
                                <option value="Week">Week(s)</option>
                                <option value="Month" selected="">Month(s)</option>
                                <option value="Year">Year(s)</option>
                            </select>
                            <p class="description">Set the duration of membership access. Note that the any future
                                payments (recurring subscription, if any) will be cancelled when the membership expires.
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- end pmpro_section_inside -->
    </div> <!-- end pmpro_section -->


    <div id="content-settings" class="pmpro_section" data-visibility="shown" data-activated="true">
        <div class="pmpro_section_toggle">
            <button class="pmpro_section-toggle-button" type="button" aria-expanded="true">
                <span class="dashicons dashicons-arrow-up-alt2"></span>
                Content Settings </button>
        </div>
        <div class="pmpro_section_inside">
            <p>Protect access to posts, pages, and content sections with built-in PMPro features. If you want to protect
                more content types, <a
                    href="https://www.paidmembershipspro.com/documentation/content-controls/?utm_source=plugin&amp;utm_medium=pmpro-membershiplevels&amp;utm_campaign=documentation&amp;utm_content=pmpro-content-settings"
                    target="_blank">read our documentation on restricting content</a>.</p>
            <table class="form-table">
                <tbody>
                    <tr class="membership_categories">
                        <th scope="row" valign="top"><label>Categories</label></th>
                        <td>
                            <p>Select: <a id="pmpro-membership-categories-checklist-select-all"
                                    href="javascript:void(0);">All</a> | <a
                                    id="pmpro-membership-categories-checklist-select-none"
                                    href="javascript:void(0);">None</a></p>
                            <script type="text/javascript">
                                jQuery('#pmpro-membership-categories-checklist-select-all').click(function () {
                                    jQuery('#pmpro-membership-categories-checklist input').prop('checked', true);
                                });
                                jQuery('#pmpro-membership-categories-checklist-select-none').click(function () {
                                    jQuery('#pmpro-membership-categories-checklist input').prop('checked', false);
                                });
                            </script>
                            <div id="pmpro-membership-categories-checklist" class="pmpro_checkbox_box pmpro_scrollable">
                                <div class="pmpro_clickable">
                                    <input type="checkbox" name="membershipcategory_50" id="membershipcategory_50"
                                        value="yes">
                                    <label for="membershipcategory_50">Aside</label>
                                </div>
                                <div class="pmpro_clickable">
                                    <input type="checkbox" name="membershipcategory_51" id="membershipcategory_51"
                                        value="yes">
                                    <label for="membershipcategory_51">Design</label>
                                </div>
                                <div class="pmpro_clickable">
                                    <input type="checkbox" name="membershipcategory_52" id="membershipcategory_52"
                                        value="yes">
                                    <label for="membershipcategory_52">Enterprise</label>
                                </div>
                                <div class="pmpro_clickable">
                                    <input type="checkbox" name="membershipcategory_53" id="membershipcategory_53"
                                        value="yes">
                                    <label for="membershipcategory_53">Enterprise</label>
                                </div>
                                <div class="pmpro_clickable">
                                    <input type="checkbox" name="membershipcategory_54" id="membershipcategory_54"
                                        value="yes">
                                    <label for="membershipcategory_54">Events</label>
                                </div>
                                <div class="pmpro_clickable">
                                    <input type="checkbox" name="membershipcategory_55" id="membershipcategory_55"
                                        value="yes">
                                    <label for="membershipcategory_55">Events</label>
                                </div>
                                <div class="pmpro_clickable">
                                    <input type="checkbox" name="membershipcategory_56" id="membershipcategory_56"
                                        value="yes">
                                    <label for="membershipcategory_56">Gadgets</label>
                                </div>
                                <div class="pmpro_clickable">
                                    <input type="checkbox" name="membershipcategory_57" id="membershipcategory_57"
                                        value="yes">
                                    <label for="membershipcategory_57">Links &amp; Quotes</label>
                                </div>
                                <div class="pmpro_clickable">
                                    <input type="checkbox" name="membershipcategory_58" id="membershipcategory_58"
                                        value="yes">
                                    <label for="membershipcategory_58">Mobile</label>
                                </div>
                                <div class="pmpro_clickable">
                                    <input type="checkbox" name="membershipcategory_59" id="membershipcategory_59"
                                        value="yes">
                                    <label for="membershipcategory_59">News</label>
                                </div>
                                <div class="pmpro_clickable">
                                    <input type="checkbox" name="membershipcategory_60" id="membershipcategory_60"
                                        value="yes">
                                    <label for="membershipcategory_60">News</label>
                                </div>
                                <div class="pmpro_clickable">
                                    <input type="checkbox" name="membershipcategory_61" id="membershipcategory_61"
                                        value="yes">
                                    <label for="membershipcategory_61">Podcasts</label>
                                </div>
                                <div class="pmpro_clickable">
                                    <input type="checkbox" name="membershipcategory_62" id="membershipcategory_62"
                                        value="yes">
                                    <label for="membershipcategory_62">Social</label>
                                </div>
                                <div class="pmpro_clickable">
                                    <input type="checkbox" name="membershipcategory_63" id="membershipcategory_63"
                                        value="yes">
                                    <label for="membershipcategory_63">Technology</label>
                                </div>
                                <div class="pmpro_clickable">
                                    <input type="checkbox" name="membershipcategory_1" id="membershipcategory_1"
                                        value="yes">
                                    <label for="membershipcategory_1">Uncategorized</label>
                                </div>
                                <div class="pmpro_clickable">
                                    <input type="checkbox" name="membershipcategory_64" id="membershipcategory_64"
                                        value="yes">
                                    <label for="membershipcategory_64">Videos</label>
                                </div>
                            </div>
                            <p class="description">
                                Select categories to bulk protect posts. Non-members will see the title and excerpt for
                                posts in these categories. You can <a
                                    href="http://pardzworld2.com/wp-admin/admin.php?page=pmpro-advancedsettings"
                                    title="Advanced Settings" target="_blank">update this setting here</a>. </p>
                        </td>
                    </tr>
                    <tr class="membership_posts">
                        <th scope="row" valign="top"><label>Single Posts</label></th>
                        <td>
                            <p><a target="_blank" href="http://pardzworld2.com/wp-admin/post-new.php">Add</a> or <a
                                    target="_blank" href="http://pardzworld2.com/wp-admin/edit.php">edit</a> a single
                                post to protect it.</p>
                        </td>
                    </tr>
                    <tr class="membership_posts">
                        <th scope="row" valign="top"><label>Single Pages</label></th>
                        <td>
                            <p><a target="_blank"
                                    href="http://pardzworld2.com/wp-admin/post-new.php?post_type=page">Add</a> or <a
                                    target="_blank"
                                    href="http://pardzworld2.com/wp-admin/edit.php?post_type=page">edit</a> a single
                                page to protect it.</p>
                        </td>
                    </tr>
                    <tr class="membership_posts">
                        <th scope="row" valign="top"><label>Other Content Types</label></th>
                        <td>
                            <p>Protect access to other content including custom post types (CPTs), courses, events,
                                products, communities, podcasts, and more. <a
                                    href="https://www.paidmembershipspro.com/documentation/content-controls/?utm_source=plugin&amp;utm_medium=pmpro-membershiplevels&amp;utm_campaign=documentation&amp;utm_content=pmpro-content-settings"
                                    target="_blank">Read our documentation on restricting content</a>.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- end pmpro_section_inside -->
    </div> <!-- end pmpro_section -->

    <div id="other-settings" class="pmpro_section" data-visibility="shown" data-activated="false">
        <div class="pmpro_section_toggle">
            <button class="pmpro_section-toggle-button" type="button" aria-expanded="true">
                <span class="dashicons dashicons-arrow-up-alt2"></span>
                Other Settings </button>
        </div>
        <div class="pmpro_section_inside" style="">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row" valign="top"><label>Disable New Signups</label></th>
                        <td><input id="disable_signups" name="disable_signups" type="checkbox" value="yes"> <label
                                for="disable_signups">Check to hide this level from the membership levels page and
                                disable registration.</label></td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <h2 class="title">Set Membership Discount</h2>
            <p>Set a membership discount for this level which will be applied when a user with this membership level is
                logged in. The discount is applied to the product's regular price, sale price, or level-specific price
                set on the edit product page.</p>
            <table>
                <tbody class="form-table">
                    <tr>
                        <th scope="row" valign="top"><label for="membership_discount">Membership Discount (%):</label>
                        </th>
                        <td>
                            <input type="number" min="0" max="100" name="membership_discount" value="">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- end pmpro_section_inside -->
    </div> <!-- end pmpro_section -->

    <p class="submit">
        <input name="save" type="submit" class="button button-primary" value="Save Level">
        <input name="cancel" type="button" class="button" value="Cancel"
            onclick="location.href='http://pardzworld2.com/wp-admin/admin.php?page=pmpro-membershiplevels';">
    </p>
</form>
                            </div>