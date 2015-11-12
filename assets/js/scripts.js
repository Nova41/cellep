/*!
 * fastshell
 * Fiercely quick and opinionated front-ends
 * https://HosseinKarami.github.io/fastshell
 * @author Marcus Martini
 * @version 1.0.3
 * Copyright 2015. MIT licensed.
 */
(function ($, window, document) {

  'use strict';
  
  /*******************************************
	 * GEOLOCATION
	 *******************************************/
  
  function showPosition(position) {   

    var latlng = position.coords.latitude + ',' + position.coords.longitude,
        url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' + latlng + '&sensor=true'; 
        
    $.getJSON(url, function (data) {
      var numero = data.results[0].address_components[0].long_name,
          endereco = data.results[0].address_components[1].short_name,
          bairro = data.results[0].address_components[2].long_name,
          cidade = data.results[0].address_components[3].long_name,
          estado = data.results[0].address_components[5].short_name,
          cep = data.results[0].address_components[7].long_name;
      
      $('.busca-cep').closest('form-line').removeClass('loading');
      $('.cep').val(cep);
      $('.busca-cep').val(endereco + ', ' + numero + ', ' + bairro + ' - ' + cidade + '/' + estado);
      $('.endereco').val(endereco + ', ' + bairro + ' - ' + cidade + '/' + estado);
    });
  }
  
  
  
  /*******************************************
   * AGENDE SUA VISITA
   *******************************************/

  function agendaVisita() {
    var dateTime = [];
    
    $.getJSON( 'http://foxxnet.asystem.com.br/integracao_site.php?lista_unidades', function( data ) {
			$.each(data[1], function(index, val) {
				if (val.nome_unidade !== null){
          $('.unidade').parent('.form-line').removeClass('loading');
          $('.unidade select').append('<option value="' + val.unidade + '">' + val.nome_unidade + '</option>');
				}
			});
		});

		$('.main').on('change', '.unidade select', function () {
      var filter = $(this).val();

      $('.melhor-dia select, .melhor-hora select').children('option').remove();
      $('.melhor-dia').parent('.form-line').addClass('loading');

      $.getJSON( 'http://foxxnet.asystem.com.br/integracao_site.php?agenda', function( data ) {
      	$('.melhor-dia').parent('.form-line').removeClass('loading');
       	$('.melhor-dia select').html('<option>__/__/____</option>');

      	$.each(data[1], function(index, val) {
      		if (val.sigla_unidade === filter) {
      			$.each(val.horarios, function(key, val) {
        			var data = key.split('-').reverse().join('/');

							$('.melhor-dia select').append('<option value="' + data + '">' + data + '</option>');
							dateTime[data] = val;
						});
      		}
    	  });
      });
    });


    $('.main').on('change', '.melhor-dia select', function () {
      var filter = $(this).val();

      $('.melhor-hora select').children('option').remove();
      $('.melhor-hora select').html('<option>--:--</option>');

      $.each(dateTime[filter], function(key, val) {
    	  var split = val.split(':'),
      		  hora = split[0] + ':' + split[1];

      	$('.melhor-hora select').append('<option value="' + hora + '">' + hora + '</option>');
      });
    });
		    
    $('.unidade').parent('.form-line').addClass('loading');
	}
    	
    	
    	
 /*******************************************
  * BUSCA EMPRESA/ESCOLA CONVENIADA 
  *******************************************/
  
  /*function buscaCorporate(type) {
    
  	$('.form-busca-' + type).on('click', '.pesquisa-btn', function (e) {
      var filter =  $('.pesquisa-' + type).val(),
          info = '';
      
      $('.results-box').addClass('loading');
  
      $.getJSON( 'http://foxxnet.asystem.com.br/integracao_site.php?corporate=' + filter, function( data ) {
        $('.results-box').removeClass('loading');
        $('.results-box').html('');
        
        if(data[0].corporate === 0) {
          
          $('.results-box').html('<div class="infobox infobox--special corte"' +
            '<p><strong>Sua ' + type + ' ainda não é conveniada com o Cel.Lep.</strong></p>' + 
            '<p>Preencha o formulário abaixo e entraremos em contato ' + 
            'para que sua ' + type + ' aproveite todas as vantagens ' + 
            'que nosso convênio oferece.</p></div>');
        
        } else {
        
  	      $.each(data[1], function(index, val) {
            
            if(val.empresa && val.empresa !== '') {
              info += '<li><a href="#' + val.empresa + '">' +
                      '<strong>' + val.empresa + '</strong><br>';
              if(val.endereco && val.endereco !== '') {
                info += val.endereco;
                info += val.numero && val.numero !== '' ? ', ' + val.numero : '';
                info += val.bairro && val.bairro !== '' ? ' - ' + val.bairro + '<br>' : '<br>';
              }
              info += val.cidade && val.cidade !== '' ? val.cidade + ' - ' : '';
              info += val.estado && val.estado !== '' ? val.estado : '';
            }
  	      });
  	      
  	      $('.results-box').append('<ul class="results-box__results">' + info + '</ul>');
  	      
  	      $('.results-box a').on('click', function(e) {
  	        var isSmall = window.matchMedia('(max-width: 768px)').matches,
  	            $input = $('#nome-' + type),
  	            $scroll = $input.closest('.form-line');
            
            $input.val($('strong', this).html());
            
            if(isSmall) {
              $.scrollTo($scroll, 800, { axis:'y' });
            } else {
              $('.content .ajax-content').scrollTo($scroll, 800);
            }
            
            e.preventDefault();
          });
        }
        
      });
        
      e.preventDefault();
     
  	});
  }*/
  // buscaCorporate('empresa');
  // buscaCorporate('escola');
  
  
  
  // scrolltop
  function scrollTop($el) {
    $('.s-box__link--open').removeClass('s-box__link--open');
    $el.addClass('s-box__link--open');
  }
  
  $('.main').on('click', '.s-box__link--open', function(e) {
    if (location.hash === $(this).attr('href')) { 
      var $target = $(this).closest('.box__header').next('.content').find('.ajax-content');
      $target.scrollTo(0, 800);
      e.preventDefault(e);
    }
  });
  
    
  
  /*******************************************
	 * GOOGLE MAPS FUNCTIONS
	 * basic creation of maps and markers
	 *******************************************/
  
	/* global google */
	 
	/*
	 *  newMap
	 *  This function will render a Google Map onto the selected jQuery element
	 *
	 *  @param	$el (jQuery element)
	 *  @return	n/a
	 */
	
	function newMap($el) {
		
		var $markers = $el.find('.marker'),
				args = {
					zoom		: 16,
					center		: new google.maps.LatLng(0, 0),
					mapTypeId	: google.maps.MapTypeId.ROADMAP
				},
				map = new google.maps.Map( $el[0], args);
		
		map.markers = [];
		
		$markers.each(function(){ addMarker( $(this), map ); });
		centerMap(map);
	
		return map;
	}
	
	
	/*
	 *  addMarker
	 *  This function will add a marker to the selected Google Map
	 *
	 *  @param	$marker (jQuery element)
	 *  @param	map (Google Map object)
	 *  @return	n/a
	 */
	
	function addMarker( $marker, map ) {
		var latlng = new google.maps.LatLng($marker.data('lat'), $marker.data('lng')),
				marker = new google.maps.Marker({
					position	: latlng,
					map			  : map,
					url       : 'waze://?ll=' + $marker.data('lat') + ',' + $marker.data('lng')
				});
	
		map.markers.push(marker);
	
		google.maps.event.addListener(marker, 'click', function() {
      window.location.href = this.url;
    });
	}
	
	
	/*
	*  centerMap
	*
	*  This function will center the map, showing all markers attached to this map
	*
	*  @param	map (Google Map object)
	*  @return	n/a
	*/
	
	function centerMap(map) {
		var bounds = new google.maps.LatLngBounds();
	
		$.each( map.markers, function( i, marker ){
			var latlng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
			bounds.extend(latlng);
		});
	
		if( map.markers.length === 1 ) {
		    map.setCenter( bounds.getCenter() );
		    map.setZoom( 16 );
		}	else {
			map.fitBounds( bounds );
		}
	}
	
	
  function calculateAndDisplayRoute($map, origin, destination) {
    var directionsService = new google.maps.DirectionsService(),
        directionsDisplay = new google.maps.DirectionsRenderer(),
        map = new google.maps.Map($map[0], {
          zoom: 7,
          center: {lat: destination.lat, lng: destination.lng}
        });

    directionsDisplay.setMap(map);
    
    directionsService.route({
      origin: origin,
      destination: destination.lat + ',' + destination.lng,
      travelMode: google.maps.TravelMode.DRIVING
    }, function(response, status) {
      if (status === google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(response);
      } else {
        console.log('Directions request failed due to ' + status);
      }
    });
  }
	
  
	 
 /*
	*  outsideClose
	*
	*  This function will simulate a 'click outside' event,
	*  to hide an element based on a 'status type' class.
	*
	*  @param	$el (jQuery element)
	*  @param	active (string or array of class names)
	*  @param	callback (callback function)
	*  @return	n/a
	*/
	 
	function outsideClose($el, active, callback) {
  	if($el.length) {
  		var removeit = $.isArray(active) ? active.join(' ') : active;
	  	
	  	$(document).on('click', function (e) {
		    if (!$el.is(e.target) && $el.has(e.target).length === 0) {
		      $el.removeClass(removeit);
		    }
			});
			
			if ($.isFunction(callback)) { callback(); }
  	}
  }
	
  
  
 /*
	*  masks
	*
	*  simple form masking
	*  use maskedinput.jquery.min.js
	*
	*  @return	n/a
	*/
	 
	function masks() {
    $('.data-nascimento').mask('99/99/9999');
    $('.cpf').mask('999.999.999-99');
    $('.cnpj').mask('99.999.999/9999-99');
    $('.cep').mask('99999-999');
    $('.busca-cep').unmask();
    
    $('.telefone').on('focusout', function() {
      var phone = $(this).val().replace(/\D/g, ''),
          mask = (phone.length > 10) ? '99 99999.999?9' : '99 9999.9999?9';
      
      $(this).unmask().mask(mask);
    }).trigger('focusout');
	}
  
  
 /*
	*  courseSelect
	*
	*  Ajax load content from $el value into .cousebox__content.
	*
	*  @param	$el (jQuery element)
	*  @return	n/a
	*/
	
  function courseSelect($el) {
  	var $target = $el.next($('.coursebox__content')),
  	    spliturl =  $el.val().split('://'),
        url = spliturl[0] === 'https' ? 'http://' + spliturl[1] : spliturl.join('://');

    $target.html('').load(url + ' .ajax-content', null, function() {
    	$target.removeClass('loading');
    });
  }
  
  
  $('body').show();
  
  /*******************************************
	 * INFOBOX
	 * loading of infobox object
	 *******************************************/
	 
	function loadInfobox($el, url, callback) {
	  var $target = $('.infobox__content', $el),
        $grid = $el.parent('.infobox-grid'),
        isSmall = window.matchMedia('(max-width: 768px)').matches,
        
        scrolling = function() {
        	if (!$('body').hasClass('home')) {
        		return isSmall ? $.scrollTo($grid, 800, { axis:'y' }) : $('.content').scrollTo($grid, 800);
        	} else {
        		return isSmall ? $.scrollTo($grid, 800, { axis:'y' }) : $('.content .ajax-content').scrollTo($grid, 800);
        	}
        };
    
    var spliturl =  url.split('://');
    url = spliturl[0] === 'https' ? 'http://' + spliturl[1] : spliturl.join('://');
    
    if (!$('.ajax-content', $el).length) {
    	$target.html('').load(url + ' .ajax-content', null, function() {
        $grid.removeClass('loading');
        
        if ($.isFunction(callback)) {
          callback();
        } else {
          if($('.acf-map', $target).length) { newMap($('.acf-map', $el)); }
        }
        
	      setTimeout( function () {
	        $target.fadeIn(800);
        	scrolling();
  	    }, 800);
      });
    } else if(!$grid.hasClass('infobox--open')) {
      setTimeout( function () {
      	scrolling();
	    }, 800);
    }
    
   	$('.infobox-grid').removeClass('infobox--open');
  	$grid.addClass('infobox--open');
	}
  
  $('.content').on('click', '.infobox', function(e) {
    if (!$(this).parent('.infobox-grid').hasClass('infobox--open')) {
      loadInfobox($(this), $(this).data('url'));
      e.preventDefault();
    }
  });


  
  /*******************************************
	 * TIMELINE & TOOLTIP
	 * loading of timeline and tooltip objects
	 *******************************************/
  
  $('.main').on('click', '.timeline__item, .has-tooltip', function(e) {
    var $tooltip = $('<span>'),
        spliturl =  $(this).attr('href').split('://'),
        url = spliturl[0] === 'https' ? 'http://' + spliturl[1] : spliturl.join('://'),
        $that = $(this),
        $wrapper = $(this);
    
    $('.tooltip').removeClass('s-tooltip--open');
    $('.timeline__item').removeClass('s-timeline__item--active');
    
    if($(this).hasClass('timeline__item')) {
    	$(this).addClass('s-timeline__item--active');
    	$wrapper = $(this).parent();
    }
    
    var isSmall = window.matchMedia('(max-width: 768px)').matches;
    
    $(this).addClass('loading');
    $wrapper.prepend(
      $tooltip.addClass('tooltip corte').html('')
      .load(url + ' .ajax-content', function() {
        $that.removeClass('loading');
        $('.tooltip', $wrapper).addClass('s-tooltip--open');

        outsideClose($('.tooltip, .timeline__item, .has-tooltip'), [
          's-timeline__item--active',
          's-tooltip--open'
        ], function() { $('.timeline--first-date').attr('style', ''); });
        
        if ($(this).closest('.timeline__wrapper').hasClass('timeline--first-date')) {
          var height = $(this).height() + 20;
          
          $(this).closest('.timeline__wrapper').css('margin-top', height);
        } else { 
          $('.timeline--first-date').attr('style', '');
        }
        
        setTimeout(function() {
  	      if(isSmall) {
            $.scrollTo($('.s-tooltip--open'), 800, { axis:'y' });
          } else {
            $('.timeline').scrollTo($('.s-tooltip--open'), 800);
          }
  	    }, 800);
      })
    );

    e.preventDefault();
  });
	
	

	// GOOGLE MAPS /////
  
	var map = null;
	
	$('.acf-map').each(function(){
		map = newMap($(this));
	});
	
	
	$('.box__link').on('click', function() {
    if(window.matchMedia('(max-width: 768px)').matches) {
      setTimeout( function () {
      	$.scrollTo($('.boxes'), 800, { axis:'y' });
	    }, 800);
    }
	});
	

	// CLICK TO CALL

	$('.click-to-call').on('click', function(e){
		$('.click-to-call__form').toggleClass('s-clicktocall__form--open');
		e.preventDefault();
	});
	
	outsideClose($('.click-to-call, .click-to-call__form'), 's-clicktocall__form--open');
	
	// SLIDER
	
	if($('body').hasClass('home')) {
  	$('.slider').slick({
  	  dots: true,
  	  infinite: true,
  	  autoplay: true,
  	  speed: 800,
  	  slidesToShow: 1
  	});
	}
	
	
	// LINKS DA HOME
	
	if($('body').hasClass('home')) {
		$(window).hashchange( function() {
			var hash = location.hash,
			    split = hash.split('/'),
					splice = hash.split('/');
					
			if (splice.length > 1) {
			  splice.splice(0,1);
			} else {
			  splice[0] = hash.substr(1);
			}
					
			var slug = splice.length > 1 ? splice.join('/') : splice[0],
			    $area = split.length > 1 ? $('.box--' + split[0].substr(1)) : $('.box--' + slug),
					url = slug;

			$('.main').attr('class', 'main s-' + slug);
			
			if (split[0] === '#institucional') {
			  $('.main').addClass('s-institucional');
			  
			  $('.box__title', $area).html('').addClass('loading')
			    .load(url + ' .ajax-title', function() {
  					var title = $('.ajax-title', this).html();
					  $('.ajax-title', this).remove();
					  $(this).removeClass('loading').append(title);
					  
					  $('.box__link', $area).attr('href', hash);
				    scrollTop($('.box__link', $area));
			  });
			  
			} else {
			  $('.main').removeClass('s-institucional');
			}
			
			if (split[0] === '#agende-sua-visita') {
			  $('.main').addClass('s-agende-sua-visita');
			  
			  $('.box__title', $area).html('').addClass('loading')
			    .load(url + ' .ajax-title', function() {
  					var title = $('.ajax-title', this).html();
					  $('.ajax-title', this).remove();
					  $(this).removeClass('loading').append(title);
					  
            $('.box__link', $area).attr('href', hash);
				    scrollTop($('.box__link', $area));
			  });
			  
			} else {
			  $('.main').removeClass('s-agende-sua-visita');
			}
			
			
			
			if(!$('.ajax-content', $area).length || split.length > 0) {
				
				if (split[0] === '#institucional') {
				  $('.content', $area).addClass('loading').html('');
				}
				
				$('.content', $area).load(url + ' .ajax-content', function() {
					$(this).removeClass('loading');
					
					if ( $('.wpcf7-form').length ) { $('.wpcf7-form').wpcf7InitForm(); }
					masks();
					
					if (split[0] === '#agende-sua-visita') { agendaVisita(); }
				// 	if (split[0] === '#para-minha-empresa') { buscaCorporate('empresa'); }
				// 	if (split[0] === '#para-minha-escola') { buscaCorporate('escola'); }
					
					if (url === 'unidades') { buscaCEP(); }

					if ($('.coursebox__select', $(this)).length) {
						$('.main').on('change', '.coursebox__select', function() {
							courseSelect($(this));
						});

						$('.coursebox__select', $(this)).each(function() {
						    courseSelect($(this));
						});
					}
					
          $.scrollTo($area, 800);
          scrollTop($('.box__link', $area));
		    });
			} else {
			  $.scrollTo($area, 800);
			  scrollTop($('.box__link', $area));
			}
		});
		
		$(window).hashchange();
	}
	
	
	
	// INDICE
	
	$('.box__anchor').on('click', function(e) {
    var slug = $(this).attr('href').split('#').join(''),
    		$box = $(this).closest('.box'),
    		$container = $('.ajax-content', $box),
    		$target = $('#part--' + slug, $container);
    
    $container.scrollTo($target, 800);
		e.preventDefault();
	});
	
	
	
	/* ADICIONAR CAMPO */
	
 	$('.add-field').click(function(e){

		$('.lista').append('<div><input type="text" name="item" size="60" /></div>');
		
		e.preventDefault();
		
	});
				
	
	/*******************************************
	 * BUSCA CEP FUNCTIONS
	 *******************************************/
	function buscaCEP() {
	  
  	$('.form-busca-cep').on('click', '.busca-cep-btn', function (e) {
  	  var origin 				= $('.busca-cep').val(),
  			  destinations 	= [];
	
  	  $.getJSON('wp-content/themes/cellep/unidades-json.php', function( data ) {
  	    $.each(data, function(index, val) {
  	  	  destinations.push(val.lat + ',' + val.lng);
  		  });
  		
  		var service = new google.maps.DistanceMatrixService();
  	  
  	  service.getDistanceMatrix({
  	    origins: [origin],
  	    destinations: destinations,
  	    travelMode: google.maps.TravelMode.DRIVING,
  	    unitSystem: google.maps.UnitSystem.METRIC
  	  }, function(response, status) {
  	  	if (status !== google.maps.DistanceMatrixStatus.OK) {
  	      console.log('Error was: ' + status);
  	    } else {
  	    	var minDist = 99999999,
  	    			closest = null;
  	    			
  	    	for (var i in data) {
  	    		var results = response.rows[0].elements;
  	    		if (results[i].distance.value < minDist) {
  	    			minDist = results[i].distance.value;
  	    			closest = data[i];
  	    		}
  	    	}
  	    	
  	    	var link = closest.link,
  	    	    slug = link.split('/unidades/')[1].slice(0,-1),
  	    	    $infobox = $('.infobox--' + slug);
  	    	
  	    	loadInfobox($infobox, link, function(){
  	    	  calculateAndDisplayRoute($('.acf-map', $infobox), origin, closest);
  	    	});
  	    }
  	  });
  	});
  	e.preventDefault();
  });
 }
	
/*******************************************
	 * REGISTER
	 * loading of register objects
	 *******************************************/
  
  $('.main').on('click', '.open-login', function(e) {
    
    $('.form__register').append('<div class="register"></div>')
      .load('login-2 .ajax-content', function() {
        var url = window.location.href;
        
        $('.form__register').toggleClass('s-register__form--open');
        $('.form-login').attr('action', url)
          .append('<input name="redirect_to" type="hidden" value="' + url + '" />');
	  });
    
    e.preventDefault();
  });
  
  $('#header').on('click', '.open-login', function(e) {
    
    $('.form__register').append('<div class="register"></div>')
      .addClass('loading').load('login-2 .ajax-content', function() {
			  $(this).removeClass('loading');
	  });
    
    e.preventDefault();
  });
  
  $('#header').on('click', '.open-register', function(e) {
    
    $('.form__register').append('<div class="register"></div>')
      .addClass('loading').load('cadastro .ajax-content', function() {
			$(this).removeClass('loading');
			masks();
	  });
    //$('.form__register').addClass('loading').load('/wp-content/themes/cellep/form-cadastro.php');
    masks();
    
    e.preventDefault();
  });
  
  $('#header').on('click', '.close__form', function(e) {
    $('.form__register').toggleClass('s-register__form--open');
    e.preventDefault();
  });
  
  outsideClose($('.form__register, .open-login'), 's-register__form--open');
  
  /*******************************************
  * AUTOMATIC ADDRESS
  * loading of address by cep
  *******************************************/
  
	$('body').on('blur', '.cep', function(){
	  getEndereco($(this), $(this).val());
	});
  
  function getEndereco($el, cep) {
    if($.trim(cep) !== ''){
      
      var $form = $el.closest('form');
      
      $el.closest('.form-line').addClass('loading');
      
      $.getJSON('http://cep.republicavirtual.com.br/web_cep.php?formato=json&cep='+cep, function(data){            
        if (data.resultado !== '0') {
           
          var endereco = (data.tipo_logradouro) + ' ' +
          (data.logradouro) + ', ' + (data.bairro) + ' - ' +
          (data.cidade) + '/' + (data.uf);
          
          $el.closest('.form-line').removeClass('loading');
          $('.endereco', $form).val(endereco);
        }
      });
    }
  }
  
  /*******************************************
  * GEOLOCATION
  * loading of location objects
  *******************************************/
  
  if (!navigator.geolocation) { $('.btn-location').remove(); }
  
  $('.main').on('click', '.btn-location', function(e) {
    $('.busca-cep').closest('form-line').addClass('loading');
    
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition);
    }
    
    e.preventDefault();
  });
  
  /*******************************************
	 * ADD FIELDS
	 * loading of add objects
	 *******************************************/
	 
	var pos = 1;
	
	function resetForm($el) {
    $el.find('input:text, input:password, input:file, select, textarea').val('');
    $el.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
  }
  
  $('.main').on('click','.add-field', function(e) {
    
    var $form   = $(this).closest('form'),
        last    = pos - 1,
        $last   = $('.input-fields[data-pos="' + last + '"]', $form),
        $clone  = $last.clone(true);
    
    resetForm($clone);
    $clone.insertAfter($last).attr('data-pos', pos);
    $clone.prepend('<a href="#" class="close__form remove-fields">x</a>');
    pos++;
    
    $('legend', $clone).html('Filho ' + pos);
    $('.data-nascimento', $clone).attr('name', 'data-nascimento' + pos).attr('id', 'data-nascimento' + pos);
    $('.idioma', $clone).attr('name', 'idioma' + pos).attr('id', 'idioma' + pos);
    $('.contato-outro-idioma', $clone).attr('name', 'contato-outro-idioma' + pos).attr('id', 'contato-outro-idioma' + pos);
    $('.qual-escola', $clone).attr('name', 'qual-escola' + pos).attr('id', 'qual-escola' + pos);
    $('.quanto-tempo', $clone).attr('name', 'quanto-tempo' + pos).attr('id', 'quanto-tempo' + pos);
    $('.nome-colegio', $clone).attr('name', 'nome-colegio' + pos).attr('id', 'nome-colegio' + pos);
    $('.periodo-estudo', $clone).attr('name', 'periodo-estudo' + pos).attr('id', 'periodo-estudo' + pos);
    
    masks();
    e.preventDefault();
  });
      
  $('.main').on('click','.remove-fields', function(e) {
    $(this).parent('.input-fields').remove();
    pos--;
    e.preventDefault();
  });
  
  
  
  $(document).on('submit.wpcf7', function () {
    setTimeout( function () {
      $('.ajax-content').scrollTo('.wpcf7-response-output', 800);
    }, 800);
  });
  
  //$('.main').on('click', '.wpcf7-submit', function(e) {
     
     //console.log('bla');
      //e.preventDefault();
  //});
  
  
})(jQuery, window, document);