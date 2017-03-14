# cf-popup
Caldera Forms as popups for list building. This is a under development. You probably should not use.


## Usage
**This plugin has no user interface, you probably should not use it**

* Add your form in one of two ways.
1) Choose one form to be used always (unless dismissed) -
```
    add_filter( 'cf_popup_select_form', function(){
        //change your form ID here
    	return Caldera_Forms_Forms::get_form( 'CF58c74ff06dabc' );
    });
```
2) Set an array of form IDs to choose from, at random, unless dismissed:

```
    add_filter( 'cf_popup_forms', function( $forms ){
        $forms[] = 'CF58c74ff06dabc';
        $forms[] = 'CFHiRoy1234';
        return $forms;
    });
```

* Setup Form Popup Options
This is optional. By default popups are delayed by 2 seconds. Options allow for HTML before or after and switching to exist intent.
```php
    add_filter( 'cf_popup_popup_options', function( $options, $form_id ){
    	return array_merge( $options, array(
    		//Is exit intent or delay? False for delay. True for exit intent
    		'exit_intent' => false,
    		//Delay time for delay
    		'delay' => 2000,
    		//HTML to show before form
    		'before' => '',
    		//HTML to show after form
    		'after' => ''
    	) );
    }, 10, 2);
```



Copyright 2017 Josh Pollock for CalderaWP LLC. License under the terms of the GPL v2 or later.
