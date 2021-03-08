<legend>Breadcrumbs / Page Titles</legend>
<div class="form-group">
<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Select page title style on product listing pages, like category pages, search result page etc">Product listing pages</span></label>
<div class="col-sm-10">
    <select name="settings[basel][basel_titles_listings]" class="form-control">
        <option value="default_bc"
        <?php if($basel_titles_listings == 'default_bc'){echo ' selected="selected"';} ?>>
        Default Style + Short Height + Boxed Width
        </option>
        <option value="default_bc full_width_bc"
        <?php if($basel_titles_listings == 'default_bc full_width_bc'){echo ' selected="selected"';} ?>>
        Default Style + Short Height + Full Width
        </option>
        <option value="default_bc normal_height_bc"
        <?php if($basel_titles_listings == 'default_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Default Style / Normal Height + Boxed Width
        </option>
        <option value="default_bc full_width_bc normal_height_bc"
        <?php if($basel_titles_listings == 'default_bc full_width_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Default Style / Normal Height + Full Width
        </option>
        <option value="minimal_bc"
        <?php if($basel_titles_listings == 'minimal_bc'){echo ' selected="selected"';} ?>>
        Minimal + Short Height + Boxed Width
        </option>
        <option value="minimal_bc full_width_bc"
        <?php if($basel_titles_listings == 'minimal_bc full_width_bc'){echo ' selected="selected"';} ?>>
        Minimal + Short Height + Full Width
        </option>
        <option value="minimal_bc normal_height_bc"
        <?php if($basel_titles_listings == 'minimal_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal + Normal Height + Boxed Width
        </option>
        <option value="minimal_bc full_width_bc normal_height_bc"
        <?php if($basel_titles_listings == 'minimal_bc full_width_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal + Normal Height + Full Width
        </option>
        <option value="minimal_inline_bc"
        <?php if($basel_titles_listings == 'minimal_inline_bc'){echo ' selected="selected"';} ?>>
        Minimal Inline + Short Height
        </option>
        <option value="minimal_inline_bc normal_height_bc"
        <?php if($basel_titles_listings == 'minimal_inline_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal Inline + Normal Height
        </option>
        <option value="title_in_bc"<?php if($basel_titles_listings == 'title_in_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Short Height
        </option>
        <option value="title_in_bc normal_height_bc"<?php if($basel_titles_listings == 'title_in_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Normal Height
        </option>
        <option value="title_in_bc tall_height_bc"<?php if($basel_titles_listings == 'title_in_bc tall_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Tall Height
        </option>
        <option value="title_in_bc extra_tall_height_bc"<?php if($basel_titles_listings == 'title_in_bc extra_tall_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Extra Tall Height
        </option>
    </select>
</div>                   
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Product pages</label>
<div class="col-sm-10">
    <select name="settings[basel][basel_titles_product]" class="form-control">
        <option value="default_bc"
        <?php if($basel_titles_product == 'default_bc'){echo ' selected="selected"';} ?>>
        Default Style + Short Height + Boxed Width
        </option>
        <option value="default_bc full_width_bc"
        <?php if($basel_titles_product == 'default_bc full_width_bc'){echo ' selected="selected"';} ?>>
        Default Style + Short Height + Full Width
        </option>
        <option value="default_bc normal_height_bc"
        <?php if($basel_titles_product == 'default_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Default Style / Normal Height + Boxed Width
        </option>
        <option value="default_bc full_width_bc normal_height_bc"
        <?php if($basel_titles_product == 'default_bc full_width_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Default Style / Normal Height + Full Width
        </option>
        <option value="minimal_bc"
        <?php if($basel_titles_product == 'minimal_bc'){echo ' selected="selected"';} ?>>
        Minimal + Short Height + Boxed Width
        </option>
        <option value="minimal_bc full_width_bc"
        <?php if($basel_titles_product == 'minimal_bc full_width_bc'){echo ' selected="selected"';} ?>>
        Minimal + Short Height + Full Width
        </option>
        <option value="minimal_bc normal_height_bc"
        <?php if($basel_titles_product == 'minimal_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal + Normal Height + Boxed Width
        </option>
        <option value="minimal_bc full_width_bc normal_height_bc"
        <?php if($basel_titles_product == 'minimal_bc full_width_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal + Normal Height + Full Width
        </option>
        <option value="minimal_inline_bc"
        <?php if($basel_titles_product == 'minimal_inline_bc'){echo ' selected="selected"';} ?>>
        Minimal Inline + Short Height
        </option>
        <option value="minimal_inline_bc normal_height_bc"
        <?php if($basel_titles_product == 'minimal_inline_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal Inline + Normal Height
        </option>
        <option value="title_in_bc"<?php if($basel_titles_product == 'title_in_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Short Height
        </option>
        <option value="title_in_bc normal_height_bc"<?php if($basel_titles_product == 'title_in_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Normal Height
        </option>
        <option value="title_in_bc tall_height_bc"<?php if($basel_titles_product == 'title_in_bc tall_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Tall Height
        </option>
        <option value="title_in_bc extra_tall_height_bc"<?php if($basel_titles_product == 'title_in_bc extra_tall_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Extra Tall Height
        </option>
    </select>
</div>                   
</div>

<div class="form-group">
<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Select page title style on account and affiliate pages">Account pages</span></label>
<div class="col-sm-10">
    <select name="settings[basel][basel_titles_account]" class="form-control">
        <option value="default_bc"
        <?php if($basel_titles_account == 'default_bc'){echo ' selected="selected"';} ?>>
        Default Style + Short Height + Boxed Width
        </option>
        <option value="default_bc full_width_bc"
        <?php if($basel_titles_account == 'default_bc full_width_bc'){echo ' selected="selected"';} ?>>
        Default Style + Short Height + Full Width
        </option>
        <option value="default_bc normal_height_bc"
        <?php if($basel_titles_account == 'default_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Default Style / Normal Height + Boxed Width
        </option>
        <option value="default_bc full_width_bc normal_height_bc"
        <?php if($basel_titles_account == 'default_bc full_width_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Default Style / Normal Height + Full Width
        </option>
        <option value="minimal_bc"
        <?php if($basel_titles_account == 'minimal_bc'){echo ' selected="selected"';} ?>>
        Minimal + Short Height + Boxed Width
        </option>
        <option value="minimal_bc full_width_bc"
        <?php if($basel_titles_account == 'minimal_bc full_width_bc'){echo ' selected="selected"';} ?>>
        Minimal + Short Height + Full Width
        </option>
        <option value="minimal_bc normal_height_bc"
        <?php if($basel_titles_account == 'minimal_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal + Normal Height + Boxed Width
        </option>
        <option value="minimal_bc full_width_bc normal_height_bc"
        <?php if($basel_titles_account == 'minimal_bc full_width_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal + Normal Height + Full Width
        </option>
        <option value="minimal_inline_bc"
        <?php if($basel_titles_account == 'minimal_inline_bc'){echo ' selected="selected"';} ?>>
        Minimal Inline + Short Height
        </option>
        <option value="minimal_inline_bc normal_height_bc"
        <?php if($basel_titles_account == 'minimal_inline_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal Inline + Normal Height
        </option>
        <option value="title_in_bc"<?php if($basel_titles_account == 'title_in_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Short Height
        </option>
        <option value="title_in_bc normal_height_bc"<?php if($basel_titles_account == 'title_in_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Normal Height
        </option>
        <option value="title_in_bc tall_height_bc"<?php if($basel_titles_account == 'title_in_bc tall_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Tall Height
        </option>
        <option value="title_in_bc extra_tall_height_bc"<?php if($basel_titles_account == 'title_in_bc extra_tall_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Extra Tall Height
        </option>
    </select>
</div>                   
</div>

<div class="form-group">
<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Select page title style on shopping cart and checkout pages">Checkout</span></label>
<div class="col-sm-10">
    <select name="settings[basel][basel_titles_checkout]" class="form-control">
        <option value="default_bc"
        <?php if($basel_titles_checkout == 'default_bc'){echo ' selected="selected"';} ?>>
        Default Style + Short Height + Boxed Width
        </option>
        <option value="default_bc full_width_bc"
        <?php if($basel_titles_checkout == 'default_bc full_width_bc'){echo ' selected="selected"';} ?>>
        Default Style + Short Height + Full Width
        </option>
        <option value="default_bc normal_height_bc"
        <?php if($basel_titles_checkout == 'default_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Default Style / Normal Height + Boxed Width
        </option>
        <option value="default_bc full_width_bc normal_height_bc"
        <?php if($basel_titles_checkout == 'default_bc full_width_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Default Style / Normal Height + Full Width
        </option>
        <option value="minimal_bc"
        <?php if($basel_titles_checkout == 'minimal_bc'){echo ' selected="selected"';} ?>>
        Minimal + Short Height + Boxed Width
        </option>
        <option value="minimal_bc full_width_bc"
        <?php if($basel_titles_checkout == 'minimal_bc full_width_bc'){echo ' selected="selected"';} ?>>
        Minimal + Short Height + Full Width
        </option>
        <option value="minimal_bc normal_height_bc"
        <?php if($basel_titles_checkout == 'minimal_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal + Normal Height + Boxed Width
        </option>
        <option value="minimal_bc full_width_bc normal_height_bc"
        <?php if($basel_titles_checkout == 'minimal_bc full_width_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal + Normal Height + Full Width
        </option>
        <option value="minimal_inline_bc"
        <?php if($basel_titles_checkout == 'minimal_inline_bc'){echo ' selected="selected"';} ?>>
        Minimal Inline + Short Height
        </option>
        <option value="minimal_inline_bc normal_height_bc"
        <?php if($basel_titles_checkout == 'minimal_inline_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal Inline + Normal Height
        </option>
        <option value="title_in_bc"<?php if($basel_titles_checkout == 'title_in_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Short Height
        </option>
        <option value="title_in_bc normal_height_bc"<?php if($basel_titles_checkout == 'title_in_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Normal Height
        </option>
        <option value="title_in_bc tall_height_bc"<?php if($basel_titles_checkout == 'title_in_bc tall_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Tall Height
        </option>
        <option value="title_in_bc extra_tall_height_bc"<?php if($basel_titles_checkout == 'title_in_bc extra_tall_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Extra Tall Height
        </option>
    </select>
</div>                   
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Contact page</label>
<div class="col-sm-10">
    <select name="settings[basel][basel_titles_contact]" class="form-control">
        <option value="default_bc"
        <?php if($basel_titles_contact == 'default_bc'){echo ' selected="selected"';} ?>>
        Default Style + Short Height + Boxed Width
        </option>
        <option value="default_bc full_width_bc"
        <?php if($basel_titles_contact == 'default_bc full_width_bc'){echo ' selected="selected"';} ?>>
        Default Style + Short Height + Full Width
        </option>
        <option value="default_bc normal_height_bc"
        <?php if($basel_titles_contact == 'default_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Default Style / Normal Height + Boxed Width
        </option>
        <option value="default_bc full_width_bc normal_height_bc"
        <?php if($basel_titles_contact == 'default_bc full_width_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Default Style / Normal Height + Full Width
        </option>
        <option value="minimal_bc"
        <?php if($basel_titles_contact == 'minimal_bc'){echo ' selected="selected"';} ?>>
        Minimal + Short Height + Boxed Width
        </option>
        <option value="minimal_bc full_width_bc"
        <?php if($basel_titles_contact == 'minimal_bc full_width_bc'){echo ' selected="selected"';} ?>>
        Minimal + Short Height + Full Width
        </option>
        <option value="minimal_bc normal_height_bc"
        <?php if($basel_titles_contact == 'minimal_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal + Normal Height + Boxed Width
        </option>
        <option value="minimal_bc full_width_bc normal_height_bc"
        <?php if($basel_titles_contact == 'minimal_bc full_width_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal + Normal Height + Full Width
        </option>
        <option value="minimal_inline_bc"
        <?php if($basel_titles_contact == 'minimal_inline_bc'){echo ' selected="selected"';} ?>>
        Minimal Inline + Short Height
        </option>
        <option value="minimal_inline_bc normal_height_bc"
        <?php if($basel_titles_contact == 'minimal_inline_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal Inline + Normal Height
        </option>
        <option value="title_in_bc"<?php if($basel_titles_contact == 'title_in_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Short Height
        </option>
        <option value="title_in_bc normal_height_bc"<?php if($basel_titles_contact == 'title_in_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Normal Height
        </option>
        <option value="title_in_bc tall_height_bc"<?php if($basel_titles_contact == 'title_in_bc tall_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Tall Height
        </option>
        <option value="title_in_bc extra_tall_height_bc"<?php if($basel_titles_contact == 'title_in_bc extra_tall_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Extra Tall Height
        </option>
    </select>
</div>                   
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Blog pages</label>
<div class="col-sm-10">
    <select name="settings[basel][basel_titles_blog]" class="form-control">
        <option value="default_bc"
        <?php if($basel_titles_blog == 'default_bc'){echo ' selected="selected"';} ?>>
        Default Style + Short Height + Boxed Width
        </option>
        <option value="default_bc full_width_bc"
        <?php if($basel_titles_blog == 'default_bc full_width_bc'){echo ' selected="selected"';} ?>>
        Default Style + Short Height + Full Width
        </option>
        <option value="default_bc normal_height_bc"
        <?php if($basel_titles_blog == 'default_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Default Style / Normal Height + Boxed Width
        </option>
        <option value="default_bc full_width_bc normal_height_bc"
        <?php if($basel_titles_blog == 'default_bc full_width_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Default Style / Normal Height + Full Width
        </option>
        <option value="minimal_bc"
        <?php if($basel_titles_blog == 'minimal_bc'){echo ' selected="selected"';} ?>>
        Minimal + Short Height + Boxed Width
        </option>
        <option value="minimal_bc full_width_bc"
        <?php if($basel_titles_blog == 'minimal_bc full_width_bc'){echo ' selected="selected"';} ?>>
        Minimal + Short Height + Full Width
        </option>
        <option value="minimal_bc normal_height_bc"
        <?php if($basel_titles_blog == 'minimal_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal + Normal Height + Boxed Width
        </option>
        <option value="minimal_bc full_width_bc normal_height_bc"
        <?php if($basel_titles_blog == 'minimal_bc full_width_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal + Normal Height + Full Width
        </option>
        <option value="minimal_inline_bc"
        <?php if($basel_titles_blog == 'minimal_inline_bc'){echo ' selected="selected"';} ?>>
        Minimal Inline + Short Height
        </option>
        <option value="minimal_inline_bc normal_height_bc"
        <?php if($basel_titles_blog == 'minimal_inline_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal Inline + Normal Height
        </option>
        <option value="title_in_bc"<?php if($basel_titles_blog == 'title_in_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Short Height
        </option>
        <option value="title_in_bc normal_height_bc"<?php if($basel_titles_blog == 'title_in_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Normal Height
        </option>
        <option value="title_in_bc tall_height_bc"<?php if($basel_titles_blog == 'title_in_bc tall_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Tall Height
        </option>
        <option value="title_in_bc extra_tall_height_bc"<?php if($basel_titles_blog == 'title_in_bc extra_tall_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Extra Tall Height
        </option>
    </select>
</div>                   
</div>

<div class="form-group">
<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Select page title style on information pages. This style will also work as default fall back style">Catalog pages</span></label>
<div class="col-sm-10">
    <select name="settings[basel][basel_titles_default]" class="form-control">
        <option value="default_bc"
        <?php if($basel_titles_default == 'default_bc'){echo ' selected="selected"';} ?>>
        Default Style + Short Height + Boxed Width
        </option>
        <option value="default_bc full_width_bc"
        <?php if($basel_titles_default == 'default_bc full_width_bc'){echo ' selected="selected"';} ?>>
        Default Style + Short Height + Full Width
        </option>
        <option value="default_bc normal_height_bc"
        <?php if($basel_titles_default == 'default_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Default Style / Normal Height + Boxed Width
        </option>
        <option value="default_bc full_width_bc normal_height_bc"
        <?php if($basel_titles_default == 'default_bc full_width_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Default Style / Normal Height + Full Width
        </option>
        <option value="minimal_bc"
        <?php if($basel_titles_default == 'minimal_bc'){echo ' selected="selected"';} ?>>
        Minimal + Short Height + Boxed Width
        </option>
        <option value="minimal_bc full_width_bc"
        <?php if($basel_titles_default == 'minimal_bc full_width_bc'){echo ' selected="selected"';} ?>>
        Minimal + Short Height + Full Width
        </option>
        <option value="minimal_bc normal_height_bc"
        <?php if($basel_titles_default == 'minimal_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal + Normal Height + Boxed Width
        </option>
        <option value="minimal_bc full_width_bc normal_height_bc"
        <?php if($basel_titles_default == 'minimal_bc full_width_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal + Normal Height + Full Width
        </option>
        <option value="minimal_inline_bc"
        <?php if($basel_titles_default == 'minimal_inline_bc'){echo ' selected="selected"';} ?>>
        Minimal Inline + Short Height
        </option>
        <option value="minimal_inline_bc normal_height_bc"
        <?php if($basel_titles_default == 'minimal_inline_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Minimal Inline + Normal Height
        </option>
        <option value="title_in_bc"<?php if($basel_titles_default == 'title_in_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Short Height
        </option>
        <option value="title_in_bc normal_height_bc"<?php if($basel_titles_default == 'title_in_bc normal_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Normal Height
        </option>
        <option value="title_in_bc tall_height_bc"<?php if($basel_titles_default == 'title_in_bc tall_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Tall Height
        </option>
        <option value="title_in_bc extra_tall_height_bc"<?php if($basel_titles_default == 'title_in_bc extra_tall_height_bc'){echo ' selected="selected"';} ?>>
        Page Title Inside Breadcrumb + Extra Tall Height
        </option>
    </select>
</div>                   
</div>

<legend>Back button</legend>
<div class="form-group">
    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Adds a back button in the breadcrumbs">Back button</span></label>
    <div class="col-sm-10 toggle-btn">
    <label><input type="radio" name="settings[basel][basel_back_btn]" value="0" <?php if($basel_back_btn == '0'){echo ' checked="checked"';} ?> /><span>Disabled</span></label>
    <label><input type="radio" name="settings[basel][basel_back_btn]" value="1" <?php if($basel_back_btn == '1'){echo ' checked="checked"';} ?> /><span>Enabled</span></label>
    </div>                   
</div>


