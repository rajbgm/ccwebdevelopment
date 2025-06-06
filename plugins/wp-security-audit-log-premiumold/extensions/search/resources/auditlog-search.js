window.WsalAs = ( function() {
	var o = this;
	var attachEvents = [];

	o.AjaxUrl = window['ajaxurl'];
	o.AjaxAction = 'WsalAsWidgetAjax';

	// Listen to auditlog refresh events.
	o._WsalAuditLogRefreshed = window['WsalAuditLogRefreshed'];
	window['WsalAuditLogRefreshed'] = function() {
		o.Attach();
		o._WsalAuditLogRefreshed();

		// IP Tooltip
		jQuery( '.search-ip' ).darkTooltip( {
			animation: 'fadeIn',
			gravity: 'west',
			size: 'large',
			confirm: true,
			yes: translation_string.search,
			no: '',
			onYes: function( elem ) {
				o.SearchByIP( elem.attr( 'data-ip' ) );
			}
		} );

		// Username Tooltip
		jQuery( '.search-user' ).darkTooltip( {
			animation: 'fadeIn',
			gravity: 'west',
			size: 'large',
			confirm: true,
			yes: translation_string.search,
			no: '',
			onYes: function( elem ) {
				o.SearchByUser( elem.attr( 'data-user' ) );
			}
		} );

		// Search Help Tooltip.
		jQuery( '#wsal-search-help' ).darkTooltip( {
			animation: 'fadeIn',
			gravity: 'north',
			size: 'small',
			confirm: false,
		} );
	};

	// Search by IP callback.
	o.SearchByIP = function( ip ) {
		if ( ip.length == 0 ) return;
		window.WsalAs.real.removeAttr( 'value' );
		window.WsalAs.ClearFilters();
		jQuery( '#current-page-selector' ).attr( 'value', '1' );
		var ip_filter = 'ip:' + ip;
		o.AddFilter( ip_filter );
		jQuery( '.wsal-filter-notice-zone' ).hide();
		jQuery( '#audit-log-viewer' ).submit();
	}

	// Search by username callback.
	o.SearchByUser = function( username ) {
		if ( username.length == 0 ) return;
		window.WsalAs.real.removeAttr( 'value' );
		window.WsalAs.ClearFilters();
		jQuery( '#current-page-selector' ).attr( 'value', '1' );
		var username_filter = 'username:' + username;
		o.AddFilter( username_filter );
		jQuery( '.wsal-filter-notice-zone' ).hide();
		jQuery( '#audit-log-viewer' ).submit();
	}

	// Add callbacks to attach event.
	o.Attach = function( cb ) {
		if ( typeof cb === 'undefined' ) {
			// Call callbacks.
			for ( var i = 0; i < attachEvents.length; i++ ) attachEvents[i]();
		} else {
			// Add callbacks.
			attachEvents.push(cb);
		}
	};

	// Extend default search box.
	o.Attach( function() {
		if ( jQuery( '#wsal-as-fake-search' ).length ) return; // Already attached.

		// Select some important elements.
		o.real = jQuery( '#wsal-as-search-search-input' );
		o.real.attr( 'placeholder', 'Search by Keyword' );
		o.searchBox = jQuery( '.search-box' );

		// Nice search box effects.
		o.real.css( 'width', '160px' )
			.focus( function() {
				o.real.animate( { width: '360px' }, 'fast' );
			} )
			.blur( function() {
				o.real.animate( { width: '160px' }, 'fast' );
			});

		// o.search_dashicon = jQuery( '<span />' );
		// o.search_dashicon.attr( 'id', 'dashicon-after-search' );
		// o.search_dashicon.attr( 'class', 'dashicons dashicons-search' );

		// Search box help.
		o.search_help = jQuery( '<span />' );
		o.search_help.attr( 'id', 'wsal-search-help' );
		o.search_help.text( '?' );
		o.search_help.attr( 'data-darktooltip', translation_string.search_tooltip );

		// Attach filter counter and dropdown.
		o.fake = jQuery('<span id="wsal-as-fake-search" class="wsal-as-fake-search"/>');

		// Filters list.
		o.list = jQuery('.wsal-as-filter-list');
		o.real.before(o.fake).appendTo(o.fake);
		// ( o.search_dashicon ).insertAfter( o.real );

		// Clear Search Button
		o.clearBtn = jQuery( '<input />' );
		o.clearBtn.attr( 'id', 'clear-search' );
		o.clearBtn.addClass( 'button' );
		o.clearBtn.addClass( 'wsal-button' );
		o.clearBtn.val( translation_string.clear_search );
		o.clearBtn.attr( 'type', 'button' );
		o.clearBtn.attr( 'disabled', true );
		( o.clearBtn ).insertAfter( jQuery( '#search-submit' ) ); // Insert Clear Search button after Search button.

		// Moves the full container for the save and load filters buttons.
		// Ideally all of this moves to php instead.
		jQuery( '.load-search-container' ).detach().insertAfter( '#search-submit' );
		jQuery( '.save-search-container' ).detach().insertAfter( '#search-submit' );

		var submitButton       = document.getElementById( 'search-submit' );
		submitButton.outerHTML = submitButton.outerHTML.replace( /^\<input/, '<button' ) + submitButton.value + '</button>';
		jQuery( '#search-submit' ).addClass( 'wsal-button dashicons-before dashicons-search' );
		jQuery( '#search-submit' ).attr( 'type', 'submit' );
		jQuery( '#search-submit' ).css( 'margin-left', '-3px' );

		// Save Search Button
		o.saveBtn = jQuery( '#save-search-btn' );

		// Load Search Button
		o.loadBtn = jQuery( '#load-search-btn' );

		// Save Search Popup
		o.save_popup = jQuery( '.wsal-save-popup' );
		o.save_error = jQuery( '#wsal-save-search-error' );
		o.save_btn   = jQuery( '#wsal-save-search-btn' );

		o.saveBtn.click( function() {
			o.load_popup.hide( 'fast' );
			o.save_popup.slideToggle( 'fast' );
		} );

		// Load Search Popup
		o.load_popup = jQuery( '.wsal-load-popup' );
		o.load_list  = jQuery( '.wsal-load-result-list' );

		jQuery( '.wsal-load-popup .close' ).click( function(e) {
			e.preventDefault();
			o.load_popup.fadeOut('fast');
		} );

		o.real.keypress( function( event ) {
			if ( 13 === event.which ) {
				console.log( 'submit' );
				jQuery( '#audit-log-viewer' ).submit();
			}
			o.clearBtn.removeAttr( 'disabled' );
		} );

		jQuery( '#wsal_as_widget_username, #wsal_as_widget_usermail, #wsal_as_widget_firstname, #wsal_as_widget_lastname, #wsal_as_widget_postid, #wsal_as_widget_postname, .wsal_as_filters_datewidget' ).keypress(
			function( event ) {
				if ( 13 === event.which ) {
					event.preventDefault();
					jQuery( event.target ).next( '.wsal-filter-add-button' ).click();
					jQuery( event.target ).val( '' );
				}
			}
		);

		jQuery( '#wsal_as_widget_ip' ).keypress(
			function( event ) {
				if ( 13 === event.which ) {
					event.preventDefault();
					jQuery( '#wsal-add-ip-filter' ).click();
					jQuery( event.target ).val( '' );
				}
			}
		);

		jQuery( '#wsal_as_widget_event' ).keydown(
			function( event ) {
				if ( 13 === event.which ) {
					event.preventDefault();
					if ( ! jQuery( '#wsal-add-event-filter' ).prop( 'disabled' ) ) {
						jQuery( '#wsal-add-event-filter' ).click();
						jQuery( event.target ).val( '' );
					}
				}
			}
		);

		// attach suggestion dropdown
		// TODO suggestions should cause user query to be removed and the selected filter to appear in filters box
	});

	// Add new filter.
	o.AddFilter = function( text ) {
		var filter = text.split(':');
		if ( filter[0] == 'from' || filter[0] == 'to' || filter[0] == 'on' ) {
			// Validation date format.
			if ( ! wsal_CheckDate( filter[1] ) ) {
				return;
			}
		}
		if ( ! jQuery( 'input[name="filters[]"][value="' + text + '"]' ).length ) {
			o.list.append(
				jQuery( '<span/>' ).append(
					jQuery( '<input type="text" name="filters[]" value="' + text + '"/>' ),
					jQuery( '<a href="javascript:;" title="' + translation_string.remove + '">&times;</a></span>' )
						.click( function() {
							jQuery( this ).parents( 'span:first' ).fadeOut( 'fast', function() {
								jQuery( this ).remove();
								o.CountFilters();
							});
						})
				)
			);
			o.clearBtn.removeAttr( 'disabled' );
		}
		o.CountFilters();
	};

	// Remove existing filters.
	o.ClearFilters = function() {
		o.list.html('');
	};

	// Update filter count.
	o.CountFilters = function() {
		var count = o.list.find( '>span' ).length;
		o.list[count === 0 ? 'addClass' : 'removeClass']('no-filters');
	};

	// Add new load result.
	o.AddSaveSearch = function( search ) {
		if ( ! search ) {
			var result_item = jQuery( '<div></div>' );
			result_item.addClass( 'saved-result-item' );

			var result_name = jQuery( '<span></span>' );
			result_name.addClass( 'save-result-name' );
			result_name.text( translation_string.nothing );

			result_item.append( result_name );
			o.load_list.append( result_item );
			return;
		}
		var result_item = jQuery( '<div></div>' );
		result_item.addClass( 'saved-result-item' );

		var result_name = jQuery( '<span></span>' );
		result_name.addClass( 'save-result-name' );
		result_name.text( search['name'] );

		var result_load = jQuery( '<a></a>' );
		result_load.addClass( 'button-primary load-search-result' );
		result_load.text( translation_string.search_load );
		result_load.click( function( e ) {
			e.preventDefault();
			o.real.val( search.search_input );
			o.list.empty();
			if ( search.filters && search.filters.length > 0 ) {
				for ( var i = 0; i < search.filters.length; i++ ) {
					o.AddFilter( search.filters[i] );
				}
			}
			o.load_popup.fadeOut( 'fast' );
		} );

		var result_load_run = jQuery( '<a></a>' );
		result_load_run.addClass( 'button-primary load-run-search-result' );
		result_load_run.text( translation_string.search_run );
		result_load_run.click( function( e ) {
			e.preventDefault();
			o.real.empty();
			o.real.val( search.search_input );
			o.list.empty();
			if ( search.filters && search.filters.length > 0 ) {
				for ( var i = 0; i < search.filters.length; i++ ) {
					o.AddFilter( search.filters[i] );
				}
			}
			jQuery( '#audit-log-viewer' ).submit();
		} );

		var delete_search = jQuery( '<a></a>' );
		delete_search.addClass( 'button-primary delete-search-result' );
		delete_search.text( translation_string.search_delete );

		// Delete ajax request.
		delete_search.click( function( e ) {
			e.preventDefault();
			delete_search.text( translation_string.search_deleting );

			// Get values of request.
			var load_nonce 	= jQuery( '#load_saved_search_field' ).val();
			var admin_url 	= jQuery( '#wsal-admin-url' ).val();
			var delete_search_request = jQuery.ajax( {
				url : admin_url,
				type : "POST",
				data : {
					nonce : load_nonce,
					name : search.name,
					action : "wsal_delete_save_search",
				},
				dataType : "json"
			} );

			delete_search_request.done( function( response ) {
				if ( response.success ) {
					delete_search.text( translation_string.search_deleted );
					result_item.fadeOut( 'slow' );
					// document.location.reload();
				} else {
					console.log( response.message );
				}
			});

			delete_search_request.fail( function( jqXHR, textStatus ) {
				console.log( "Request Failed: " + textStatus );
			});
		} );

		result_item.append( result_name );
		result_item.append( result_load );
		result_item.append( result_load_run );
		result_item.append( delete_search );

		o.load_list.append( result_item );

	}

	return o;
})();

jQuery( document ).ready( function( $ ) {
	window.WsalAs.Attach();
	wsal_CreateDatePicker($, $('#wsal_as_widget_from'), null);
	wsal_CreateDatePicker($, $('#wsal_as_widget_to'), null);
	wsal_CreateDatePicker($, $('#wsal_as_widget_on'), null);

	var wsal_search = window.WsalAs.real;
	if ( wsal_search.val() != ' ' || wsal_search.val().length === 0 ) {
		window.WsalAs.clearBtn.removeAttr( 'disabled' );
		window.WsalAs.fake.val( '' );
	}

	// Clear Search Button JS.
	$( window.WsalAs.clearBtn ).click( function( event ) {
		event.preventDefault();
		if ( 'disabled' == $( window.WsalAs.clearBtn ).attr( 'disabled' ) ) return;
		window.WsalAs.real.removeAttr( 'value' );
		window.WsalAs.ClearFilters();

		// Get URL.
		var locationURL = window.location.href;
		var searchStr = 'wsal-auditlog';
		var searchIndex = locationURL.search( searchStr ); // Search for wsal-auditlog value in URL.
		searchIndex += searchStr.length; // Add the length of the searched string to the index.
		window.location.href = locationURL.substr(0, searchIndex); // Redirect.
	} );

	// Manually add ip.
	$( '#wsal-add-ip-filter' ).click( function( event ) {
		event.preventDefault();
		var ip = $( 'input#wsal_as_widget_ip[data-prefix="ip"]' );
		var ip_value = ip.val();
		if ( ip_value.length == 0 ) return;
		var ip_filter_value = 'ip:' + ip_value;
		window.WsalAs.AddFilter( ip_filter_value );
		ip.removeAttr( 'value' );
	} );

	// Trigger search on ENTER.
	$( window.WsalAs.real ).keypress( function( event ) {
		if ( 13 === event.which ) {
			$( '#audit-log-viewer' ).submit();
		}
	} );

	/**
	 * Load Search Results Ajax Request.
	 *
	 * @since 1.1.7
	 */
	var load_btn = $( window.WsalAs.loadBtn );
	var saved_searches; // To store saved search results.
	load_btn.click( function( event ) {

		event.preventDefault();
		load_btn.text( translation_string.search_loading );
		window.WsalAs.save_popup.hide();

		// Get values of request.
		var load_nonce = $( '#load_saved_search_field' ).val();
		var admin_url = $( '#wsal-admin-url' ).val();

		// Get results list container.
		var load_popup = $( window.WsalAs.load_popup );
		var load_list = $( window.WsalAs.load_list );
		load_list.empty();

		var load_saved_search_request = $.ajax( {
			url : admin_url,
			type : "POST",
			data : {
				nonce : load_nonce,
				action : "wsal_get_save_search",
			},
			dataType : "json"
		} );

		load_saved_search_request.done( function( response ) {
			if ( response.success ) {
				load_btn.text( translation_string.btn_load );
				load_popup.fadeIn( 'fast' );
				if ( response.search_results ) {
					var search_count = response.search_results.length;
					saved_searches = response.search_results;
				} else {
					window.WsalAs.AddSaveSearch();
				}

				for ( var i = 0; i < search_count; i++ ) {
					window.WsalAs.AddSaveSearch( response.search_results[i] );
				}
			} else {
				load_btn.text( translation_string.btn_load );
				load_popup.fadeIn( 'fast' );
				window.WsalAs.AddSaveSearch();
				console.log( response.message );
			}
		});

		load_saved_search_request.fail( function( jqXHR, textStatus ) {
			console.log( "Request Failed: " + textStatus );
		});
	} );

	// Search save name pattern detection.
	$( '#wsal-save-search-name' ).on( "change keyup paste", function() {
		var search_name = $( this ).val();
		window.WsalAs.save_error.hide();
		window.WsalAs.save_btn.removeAttr( 'disabled' );
		var name_length = search_name.length;
		if ( 12 <= name_length ) {
			window.WsalAs.save_error.show();
			window.WsalAs.save_btn.attr( 'disabled', 'disabled' );
		}

		var name_pattern = /^[a-z\d\_]+$/i;
		if ( name_length && ! name_pattern.test( search_name ) ) {
			window.WsalAs.save_error.show();
			window.WsalAs.save_btn.attr( 'disabled', 'disabled' );
		}
	} );

	// IP address validation.
	var ip_error = jQuery( '<span />' );
	ip_error.addClass( 'wsal-input-error' );
	ip_error.text( translation_string.invalid_ip );
	var ip_label = jQuery( 'label[for="wsal_as_widget_ip"]' );
	ip_label.append( ip_error );

	$( '#wsal_as_widget_ip' ).on( 'change keyup paste', function() {
		var ip_value = $( this ).val();
		var ip_add_btn = $( '#wsal-add-ip-filter' );
		ip_error.hide();
		ip_add_btn.removeAttr( 'disabled' );

		// var ip_pattern = /^(?!.*\.$)((1?\d?\d|25[0-5]|2[0-4]\d)(\.|$)){4}$/;
		var ip_pattern = /^((((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))|(((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}\*)|(((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){2}\*)|(((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){1}\*))$/g;
		if ( ip_value.length && ! ip_pattern.test( ip_value ) ) {
			ip_error.show();
			ip_add_btn.attr( 'disabled', 'disabled' );
		}
	} );

	/**
	 * Search Column Filters
	 *
	 * @since 3.2.3
	 */
	jQuery( '.wsal-search-filter' ).click( function( event ) {
		var type = jQuery( this ).attr( 'id' ).substr( 19 );
		var searchContainter = jQuery( '#wsal-filter-container-' + type );
		searchContainter.slideToggle( 'fast' );
	} );

	/**
	 * Close Filter Button
	 *
	 * @since 3.2.3
	 */
	jQuery( '.wsal-filter-container-close' ).click( function() {
		var container = jQuery( this ).data( 'container-id' );
		jQuery( '#' + container ).slideUp( 'fast' );
	});

	// show/hide the filter box.
	// since 4.0.0
	jQuery( '#filter-container-toggle' ).click(
		function( event ) {
			var button           = jQuery( this );
			var filterContainter = jQuery( '#wsal-filters-container' );
			jQuery( button.parent().parent() ).addClass( 'filters-opened' );
			button.html( translation_string.filterBtnOpen );
			filterContainter.slideToggle(
				'fast',
				function() {
					if ( jQuery( filterContainter ).is( ':visible' ) ) {
						jQuery( button ).addClass( 'active' );
					} else {
						jQuery( button ).removeClass( 'active' );
						jQuery( button.parent().parent() ).removeClass( 'filters-opened' );
						button.html( translation_string.filterBtnClose );
					}
				}
			);
		}
	);

	// hide all the groupped inputs.
	jQuery( '.wsal-filter-group-inputs .filter-wrap' ).hide();
	// bind a change function to these select inputs.
	jQuery( '.wsal-filter-group-select select' ).change(
		function() {
			var options  = jQuery( this ).children();
			var selected = jQuery( this ).children( 'option:selected' ).val()
			jQuery( options ).each(
				function() {
					var item = jQuery( this ).val();
					if ( item === selected ) {
						jQuery( '.wsal-filter-wrap-' + selected ).show();
					} else {
						jQuery( '.wsal-filter-wrap-' + item ).hide();
					}
				}
			);

			jQuery( '.wsal-filter-wrap-' + selected ).show();
		}
	);
	// fire the a change on each of the input group selects.
	jQuery( '.wsal-filter-group-select select' ).each(
		function() {
			jQuery( this ).change();
		}
	);

	// Delay the filter change checker so it doesn't fire when initial filters
	// are loaded in.
	window.setTimeout(
		function() {

			var filterNoticeSessionClosed = false;
			// Fire on change of the filters area.
			jQuery( 'body' ).on(
				'DOMSubtreeModified',
				'.wsal-as-filter-list',
				function() {

					var filterNoticeZone = jQuery( '.wsal-filter-notice-zone' );
					if ( filterNoticeSessionClosed !== false || $( filterNoticeZone ).is( ':visible' ) ) {
						return;
					}
					jQuery( '.wsal-notice-message' ).html( translation_string.filterChangeMsg );
					jQuery( filterNoticeZone ).addClass( 'notice notice-error wfcm-admin-notice is-dismissible' );
					jQuery( filterNoticeZone ).slideDown();
				}
			);

			jQuery( '.wsal-filter-notice-zone .notice-dismiss' ).click(
				function() {
					jQuery( this ).parent().slideUp();
					filterNoticeSessionClosed = true;
				}
			);

			jQuery( '#wsal-filter-notice-permanant-dismiss' ).click(
				function() {
					console.log( 'permadismiss' );
					jQuery.ajax(
						{
							url : AjaxUrl,
							type : "POST",
							data : {
								notice : 'filters-changed-permanent-hide',
								action : "AjaxDismissNotice",
							},
							dataType : "json"
						}
					);
					jQuery( this ).parent().parent().slideUp();
				}
			);
		},
		500
	);
} );
