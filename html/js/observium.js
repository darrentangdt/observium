/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage js
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2015 Observium Limited
 *
 */

function url_from_form(form_id) {
  var url = document.getElementById(form_id).action;
  var partFields = document.getElementById(form_id).elements;

  var seen = {};
  for (var el, i = 0, n = partFields.length; i < n; i++) {
    el = partFields[i];
    if (el.value != '' && el.name != '') {
      var val;
      if (el.multiple) {
        var multi = [];
        for (var part, ii = 0, nn = el.length; ii < nn; ii++) {
          part = el[ii];
          if (part.selected) {
            val = part.value.replace(/\//g, '%7F'); // %7F (DEL, delete) - not defined in HTML 4 standard
            val = val.replace(/,/g, '%1F');         // %1F (US, unit separator) - not defined in HTML 4 standard
            multi.push(encodeURIComponent(val));
            //console.log(part.value);
          }
        }
        el.name = el.name.replace('[]', '');
        if (multi.length && seen[el.name] !== 1) {
          seen[el.name] = 1;
          url = url.replace(new RegExp(encodeURIComponent(el.name) + '=[^\/]+\/', 'g'), ''); // remove old var from url
          url += encodeURIComponent(el.name) + '=' + multi.join(',') + '/';
        }
      } else if (el.checked || el.type !== "checkbox") {
        val = el.value.replace(/\//g, '%7F'); // %7F (DEL, delete) - not defined in HTML 4 standard
        val = val.replace(/,/g, '%1F');       // %1F (US, unit separator) - not defined in HTML 4 standard
        if (seen[el.name] !== 1) {            // Skip duplicate vars
          seen[el.name] = 1;
          url = url.replace(new RegExp(encodeURIComponent(el.name) + '=[^\/]+\/', 'g'), ''); // remove old var from url
          url += encodeURIComponent(el.name) + '=' + encodeURIComponent(val) + '/';
        }
      }
    }
  }

  return url;
}

function form_to_path(form_id) {
  url = url_from_form(form_id);
  window.location.href = url;
}

function submitURL(form_id) {
  url = url_from_form(form_id);
  $(document.getElementById(form_id)).attr('action', url);
}

// toggle attributes readonly,disabled by form id
function toggleAttrib(attrib, form_id) {
  //console.log('attrib: '+attrib+', id: '+form_id);
  var toggle  = document.getElementById(form_id); // js object
  var element = $('#'+form_id);                   // jQuery object
  //console.log('prop: '+element.prop(attrib));
  //console.log('attr: '+element.attr(attrib));
  //console.log('Attribute: '+toggle.getAttribute(attrib));
  //var set   = !toggle.getAttribute(attrib);
  var set   = !(toggle.getAttribute(attrib) || element.prop(attrib));
  //console.log(set);

  set ? toggle.setAttribute(attrib, 1) : toggle.removeAttribute(attrib);
  if (element.prop('localName') == 'select') {
    if (attrib == 'readonly') {
      // readonly attr not supported by bootstrap-select
      set ? toggle.setAttribute('disabled', 1) : toggle.removeAttribute('disabled');
    }
    if (element.hasClass('selectpicker')) {
      // bootstrap select
      element.selectpicker('refresh'); // re-render selectpicker
      //console.log('bootstrap-select');
    }
    else if (toggle.hasAttribute('data-toggle') && toggle.getAttribute('data-toggle') == 'tagsinput') {
      // bootstrap tagsinput
      element.tagsinput('refresh'); // re-render tagsinput
      //console.log('bootstrap-tagsinput');
    }
  } else if (toggle.hasAttribute('data-toggle') && toggle.getAttribute('data-toggle').substr(0,6) == 'switch') {
    // bootstrap switch
    element.bootstrapSwitch("toggle" + attrib.charAt(0).toUpperCase() + attrib.slice(1));
    //console.log('bootstrap-switch');
  } else if (toggle.hasAttribute('data-format')) {
    set ? $('#'+form_id+'_div').datetimepicker('disable') : $('#'+form_id+'_div').datetimepicker('enable');
    //console.log('bootstrap-datetime');
    //console.log($('#'+form_id+'_div'));
  } else if (element.prop('type') == 'submit') {
    // submit buttons
    //if (attrib == 'disabled') {
      set ? element.addClass('disabled') : element.removeClass('disabled');
    //}
  }
  //console.log(toggle);
}

// Hide/show div by id or alert class (default)
function showDiv(checked, id) {
  id = typeof id !== 'undefined' ? '[id=' + id + ']' : '.alert';
  //console.log($(id));
  if (checked) {
    $(id).hide();
  } else {
    $(id).show();
  }
}

// This function open links by onclick event and prevent duplicate windows when Meta/Ctrl key pressed
function openLink(url) {
  document.onclick = function (event) {
    //console.log(link);
    //console.log(event);
    //console.log(event.target, event.target.nodeName);
    //console.log("Meta: " + event.metaKey, "Ctrl: " + event.ctrlKey);

    if (event.metaKey || event.ctrlKey) {
      // Open link in new tab (except on A tags)
      if (event.target.nodeName != "A") {
        window.open(url, '_blank');
      }
    } else {
      // open link in current window/tab
      location.href=url;
    }
  }
}

// jQuery specific observium additions
jQuery(document).ready(function() {
  // Enable bootstrap-switch by default for data-toggle="switch" attribute
  // See options here: http://www.bootstrap-switch.org/documentation-3.html
  $('[data-toggle="switch"]').bootstrapSwitch();
  
  // Preconfigured switch-mini
  $('[data-toggle="switch-mini"]').bootstrapSwitch({
    size:        'mini',
    onText:       'Yes',
    offText:       'No'
  });

  // Preconfigured switch-revert
  $('[data-toggle="switch-revert"]').bootstrapSwitch({
    size:        'mini',
    onText:        'No',
    onColor:   'danger',
    offText:      'Yes',
    offColor: 'primary'
  });

  // Preconfigured switch-bool
  $('[data-toggle="switch-bool"]').bootstrapSwitch({
    size:        'mini',
    onText:      'True',
    onColor:  'primary',
    offText:    'False',
    offColor:  'danger'
  });

  // Bootstrap classic tooltips
  $('[data-toggle="tooltip"]').tooltip();

  // Qtip tooltips
  $(".tooltip-from-element").each(function() {
    var selector = '#' + $(this).data('tooltip-id');
    //console.log(selector);
    $(this).qtip({
      content: {
              text: $(selector)
      },
      style: {
              classes: 'qtip-bootstrap',
      },
      position: {
              target: 'mouse',
              viewport: $(window),
              adjust: {
                x: 7, y: 15,
                mouse: false, // don't follow the mouse
                method: 'shift'
              }
      },
      hide: {
              fixed: true // Non-hoverable by default
      }
    });
  });

  $("[data-rel='tooltip']").qtip({
    content: {
            attr: 'data-tooltip'
    },
    style: {
            classes: 'qtip-bootstrap',
    },
    position: {
            //target: 'mouse',
            viewport: $(window),
            adjust: {
                x: 7, y: 15,
                //mouse: false, // don't follow the mouse
                method: 'shift'
            }
    }
  });

  $('.entity-popup').each(function() {
    var entity_id   = $(this).data('eid');
    var entity_type = $(this).data('etype');

    $(this).qtip({

      content:{
          //text: '<img class="" style"margin: 0 auto;" src="images/loading.gif" alt="Loading..." />',
          text: '<big><i class="icon-spinner icon-spin text-center vertical-align" style="width: 100%;"></i></big>',
          ajax:{
              url: 'ajax/entity_popup.php',
              type: 'POST',
              loading: false,
              data: { entity_type: $(this).data('etype'), entity_id: $(this).data('eid') },
          }
      },
      style: {
              classes: 'qtip-bootstrap',
      },
      position: {
              target: 'mouse',
              viewport: $(window),
              adjust: {
                x: 7, y: 15,
                mouse: false, // don't follow the mouse
                method: 'shift'
              }
      },
      hide: {
        //target: false, // Defaults to target element
        //event: 'click mouseleave', // Hide on mouse out by default
        //effect: true,       // true - Use default 90ms fade effect
        //delay: 0, // No hide delay by default
        fixed: true, // Non-hoverable by default
        //inactive: false, // Do not hide when inactive
        //leave: 'window', // Hide when we leave the window
        //distance: false // Don't hide after a set distance
      },
    });
  });

  $('.tooltip-from-data').qtip({
    content: {
            attr: 'data-tooltip'
    },
    style: {
            classes: 'qtip-bootstrap',
    },
    position: {
              target: 'mouse',
              viewport: $(window),
              adjust: {
                x: 7, y: 15,
                mouse: false, // don't follow the mouse
                method: 'shift'
              }
    },
    hide: {
              fixed: true // Non-hoverable by default
    }
  });

});

var toggle_visibility = (function(){
   function toggle(cl){
       var els = document.getElementsByClassName(cl);
       for(var i=0; i<els.length; ++i){
          var s = els[i].style;
          s.display = s.display==='none' ? 'block' : 'none';
       };
   }
   return function(cl){
      if(cl instanceof Array){
         for(var i=0; i<cl.length; ++i){
            toggle(cl[i]);
         }
      }else{
         toggle(cl);
      }
   };
})();
