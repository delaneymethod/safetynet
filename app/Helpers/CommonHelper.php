<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
|
| This file is where you may define all of the helpers that are handled
| by your application.
|
*/

if (!function_exists('imageExists')) {
	/**
	 * Checks the url and returns true or false.
	 *
	 * @return boolean
	 */
	function imageExists(string $address) : bool
	{
		if (stripos($address, 'localhost') !== false) {
			// Local file...
			$address = str_replace(config('app.url').DIRECTORY_SEPARATOR, '', $address);
				
			return (file_exists($address)) ? true : false;
		} else {
			// Remote file
			$headers = @get_headers($address);
			
			return ($headers[0] === 'HTTP/1.1 200 OK') ? true : false;	
		}
	}
}

if (!function_exists('isCp')) {
	/**
	 * Checks the path against the request path and return true or false.
	 *
	 * @return string
	 */
	function isCp($path)
	{
		if (is_array($path)) {
			return in_array(request()->path(), $path) ? true : false;
		}
		
		return request()->is($path.'*') ? true : false;
	}
}

if (!function_exists('setActive')) {
	/**
	 * Checks the path against the request path and return true or false.
	 *
	 * @return string
	 */
	function setActive($path)
	{
		// If homepage
		if ($path === '') {
			$path = DIRECTORY_SEPARATOR;
		}
		
		if (is_array($path)) {
			return in_array(request()->path(), $path) ? 'active' : '';
		}
		
		return request()->is($path.'*') ? 'active' : '';
	}
}

if (!function_exists('setClass')) {
	/**
	 * Checks the path against the request path and return true or false.
	 *
	 * @return string
	 */
	function setClass($path, $class = 'active')
	{
		if (is_array($path)) {
			return in_array(request()->path(), $path) ? $class : '';
		}
		
		return request()->is($path.'*') ? $class : '';
	}
}

if (!function_exists('currentYear')) {
	/**
	 * Gets the current year
	 *
	 * @return integer
	 */
	function currentYear()
	{
		return date('Y');
	}
}

if (!function_exists('slugToTitle')) {
	/**
	 * Creates a title based on slug
	 */
	function slugToTitle($string)
	{
    	$string = str_replace(array('_', '-'), array(' '), $string);
		
		$string = ucwords($string);
		
		$string = str_replace(['Ip'], ['IP'], $string);
		
		return $string;
	}
}

if (!function_exists('flash')) {
	/**
	 * Creates new session flash message
	 */
	function flash($message, $level = 'info')
	{
		session()->flash('status', $message);

		$levels = ['success', 'info', 'warning', 'danger'];

		// Do a quick check to make sure we're only setting available classes from Bootstrap.
		if (in_array($level, $levels)) {
			session()->flash('status_level', $level);
		} else {
			session()->flash('status_level', 'info');
		}
	}
}

/**
 * Outputs the html checked attribute.
 *
 * Compares the first two arguments and if identical marks as checked
 *
 * @param mixed 	$checked 			One of the values to compare
 * @param mixed 	$current (true) 	The other value to compare if not just true
 * @param bool  	$echo    			Whether to echo or just return the string
 *
 * @return string html attribute or empty string
 */
if (!function_exists('checked')) {
	function checked($checked, $current = true, $echo = true ) 
	{
		return checkedSelectedHelper($checked, $current, $echo, 'checked');
	}
}

/**
 * Outputs the html selected attribute.
 *
 * Compares the first two arguments and if identical marks as selected
 *
 * @param mixed 	$selected 			One of the values to compare
 * @param mixed 	$current  (true) 	The other value to compare if not just true
 * @param bool  	$echo     			Whether to echo or just return the string
 *
 * @return string html attribute or empty string
 */
if (!function_exists('selected')) {
	function selected($selected, $current = true, $echo = true) 
	{
		return checkedSelectedHelper($selected, $current, $echo, 'selected');
	}
}

/**
 * Outputs the html disabled attribute.
 *
 * Compares the first two arguments and if identical marks as disabled
 *
 * @param mixed 	$disabled 			One of the values to compare
 * @param mixed 	$current  (true) 	The other value to compare if not just true
 * @param bool  	$echo     			Whether to echo or just return the string
 *
 * @return string html attribute or empty string
 */
if (!function_exists('disabled')) { 
	function disabled($disabled, $current = true, $echo = true) 
	{
		return checkedSelectedHelper($disabled, $current, $echo, 'disabled');
	}
}

/**
 * Private helper function for checked, selected, and disabled.
 *
 * @param mixed 	$helper 			One of the values to compare
 * @param mixed  	$current (true) 	The other value to compare if not just true
 * @param bool   	$echo 				Whether to echo or just return the string
 * @param string 	$type 				The type of checked|selected|disabled we are doing
 *
 * @return string 	html attribute or empty string
 */
if (!function_exists('checkedSelectedHelper')) { 
	function checkedSelectedHelper($helper, $current, $echo, $type) 
	{
		$result = '';
		
		if ((string) $helper === (string) $current) {
			$result = " $type='$type'";
		}
		
		if ($echo) {
			echo $result;
		}
		
		return $result;
	}
}

if (!function_exists('showField')) { 
	function showField($arguments, $oldValue = '', $tabIndex = 1) 
	{
		$symbol = '';
			
		$attributes = '';
		
		$optionsMarkup = '';
		
		if ($arguments['template_id'] > 1) {
			$arguments['required'] = false;
		}
		
		if (!empty($arguments['required']) && $arguments['required'] === true) {
			$symbol = ' <span class="text-danger">*</span>';
			
			$attributes = ' required="required"';
		}
		
		if (!empty($oldValue)) {
			$arguments['value'] = $oldValue;
		}
		
		switch ($arguments['type']) {
			case 'text':
			case 'password':
			case 'number':
				if (str_contains(strtolower($arguments['id']), 'image')) {
					printf('
						<label for="%3$s" class="control-label font-weight-bold">%7$s%9$s</label>
						<div class="input-group">
							<input type="%1$s" name="%2$s" id="%3$s" class="%4$s" autocomplete="off" placeholder="%5$s" value="%6$s"%8$s tabindex="%10$s" aria-describedby="helpBlock_%3$s" />
							<span class="input-group-btn"><a href="javascript:void(0);" title="Select Asset" data-toggle="modal" data-target="#%3$s-browse-modal" data-field_id="%3$s" data-value="%6$s" class="btn btn-outline-secondary">Select Asset</a>
								<a href="javascript:void(0);" title="Clear Asset" id="%3$s-reset-field" class="btn btn-outline-secondary">Clear Asset</a>
							</span>
						</div>
						<div class="modal fade" id="%3$s-browse-modal" tabindex="-1" role="dialog" aria-labelledby="%3$s-browse-modal-label" aria-hidden="true">
							<div class="modal-dialog modal-lg modal-xl" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="%3$s-browse-modal-label">Assets</h5>
									</div>
									<div class="modal-body">
										<div class="container-fluid">
											<div class="row no-gutters">
												<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 text-left">
													<div id="%3$s-container"></div>
												</div>
												<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-center">
													<div id="%3$s-selected-asset-preview"></div>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<div class="container-fluid">
											<div class="row d-flex h-100 justify-content-start">
												<div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 align-self-center align-self-sm-center align-self-md-left align-self-lg-left align-self-xl-left">
													<div class="text-center text-sm-center text-md-left text-lg-left text-xl-left selected-asset" id="%3$s-selected-asset"></div>
												</div>
												<div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 text-center text-sm-center text-md-center text-lg-right text-xl-right align-self-center">
													<button type="button" class="btn btn-primary" id="%3$s-select-asset">Insert</button>
													<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					', $arguments['type'], $arguments['name'], $arguments['id'], $arguments['class'], $arguments['placeholder'], $arguments['value'], $arguments['label'], $attributes, $symbol, $tabIndex);
				} else {
					printf('
						<label for="%3$s" class="control-label font-weight-bold">%7$s%9$s</label>
						<input type="%1$s" name="%2$s" id="%3$s" class="%4$s" autocomplete="off" placeholder="%5$s" value="%6$s"%8$s tabindex="%10$s" aria-describedby="helpBlock_%3$s" />
					', $arguments['type'], $arguments['name'], $arguments['id'], $arguments['class'], $arguments['placeholder'], $arguments['value'], $arguments['label'], $attributes, $symbol, $tabIndex);
				}
			 
				break;
			
			case 'textarea':
				printf('
					<label for="%2$s" class="control-label font-weight-bold">%6$s%8$s</label>
					<textarea name="%1$s" id="%2$s" class="%3$s" autocomplete="off" placeholder="%4$s" rows="5" cols="50" tabindex="%9$s" aria-describedby="helpBlock_%2$s" %7$s>%5$s</textarea>
				', $arguments['name'], $arguments['id'], $arguments['class'], $arguments['placeholder'], $arguments['value'], $arguments['label'], $attributes, $symbol, $tabIndex);
				
				break;
			
			case 'select':
			case 'multiselect':
				if (!empty($arguments['options']) && is_array($arguments['options'])) {
					foreach ($arguments['options'] as $key => $label) {
						$position = array_search($key, $arguments['default'], true);
						
						if ($position !== false) {
							$selected = selected($arguments['default'][$position], $key, false);
						} else {
							$selected = '';
						}
						
						$optionsMarkup .= sprintf('<option value="%s" %s>%s</option>', $key, $selected, $label);
					}
					
					if ($arguments['type'] === 'multiselect') {
						$attributes = ' multiple="multiple" ';
					}
					
					printf('
						<label for="%2$s" class="control-label font-weight-bold">%4$s%7$s</label>
						<select name="%1$s[]" id="%2$s" class="%3$s" tabindex="%8$s" aria-describedby="helpBlock_%2$s" %5$s>%6$s</select>
					', $arguments['name'], $arguments['id'], $arguments['class'], $arguments['label'], $attributes, $optionsMarkup, $symbol, $tabIndex);
				}
				
				break;
			
			case 'radio':
			case 'checkbox':
				if (!empty($arguments['options']) && is_array($arguments['options'])) {
					foreach ($arguments['options'] as $key => $label) {
						$position = array_search($key, $arguments['default'], true);
						
						if ($position !== false) {
							$checked = checked($arguments['default'][$position], $key, false);
						} else {
							$checked = '';
						}
						
						$optionsMarkup .= sprintf('
							<div class="form-check">
								<label for="%2$s_%4$s" class="control-label"><input type="%4$s" name="%1$s[]" id="%2$s_%5$s" value="%5$s" %6$s tabindex="%8$s" aria-describedby="helpBlock_%2$s" /> %6$s</label>
							</div>
						', $arguments['name'], $arguments['id'], $arguments['class'], $arguments['type'], $key, $checked, $label, $tabIndex);
					}
					
					printf('
						<label class="control-label font-weight-bold">%1$s%3$s</label>
						<fieldset>%2$s</fieldset>
					', $arguments['label'], $optionsMarkup, $symbol);
				}
				
				break;
				
			case 'file':
				printf('
					<label for="%3$s" class="control-label font-weight-bold">%7$s%9$s</label>
					<input type="%1$s" name="%2$s" id="%3$s" class="%4$s" autocomplete="off" placeholder="%5$s" value="%6$s"%8$s tabindex="%10$s" aria-describedby="helpBlock_%3$s" />
				', $arguments['type'], $arguments['name'], $arguments['id'], $arguments['class'], $arguments['placeholder'], $arguments['value'], $arguments['label'], $attributes, $symbol, $tabIndex);
				
				break;
		}
	
		if (!empty($arguments['instructions'])) {
			printf('<span id="helpBlock_%2$s" class="form-control-feedback form-text text-muted">- %1$s</span>', $arguments['instructions'], $arguments['id']);
		}
	}
}

if (!function_exists('recursiveCollect')) {
	function recursiveCollect(array $array)
	{
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$value = recursiveCollect($value);
				
				$array[$key] = $value;
			}
		}
	
		return collect($array);
	}
}

if (!function_exists('recursiveObject')) {	
	function recursiveObject(array $array)
	{
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$value = recursiveObject($value);
				
				$array[$key] = $value;
			}
		}
	
		return (object) $array;
	}
}
