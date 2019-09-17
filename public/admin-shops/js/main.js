//login.js + concept.js
function getYoutubeCode(url)
{

    var regExp = /^((https?:\/\/)?(w{0,3}\.)?youtu(\.be|(be|be-nocookie)\.\w{2,3}\/))((watch\?v=|v|embed)?[\/]?([a-zA-Z0-9-_]{11}))/i;
    var match = url.match(regExp);


    if (match && match[8].length == 11)
		{
        return '<iframe width="360" height="203" src="//www.youtube.com/embed/' + match[8] + '" frameborder="0" allowfullscreen></iframe>';
    } else
		{
        return '<span style="color:red;">Видео не найдено</span>';
    }
}

function globalLoading(show)
{
	var fix = $('.fixed-screen-overlay');
	if(show)
	{
		if(fix.length==0)
		{
				fix = $('<div class="fixed-screen-overlay" style="position:fixed; left:0;top:0;bottom:0;right:0;z-index:10000;"></div>');
				$('body').append(fix);
		}
		fix.ploading({action:'show'});
	}
	else
	{
			fix.on('pl:spinner:hide', function()
			{
				fix.remove();
			});
			fix.ploading({action:'hide'});
	}
}


$(function()
{

	$('.csv-button').on('click', function(e)
	{
		e.preventDefault();

		var res = {};
		$('input.filter[type=hidden]').each(function()
		{
			res[$(this).attr('name')] = $(this).val();
		});


		for (var k in JS_REQUEST)
		{
	  		if(k!='page')
	  		{
				res[k] = JS_REQUEST[k];
	  		}
		}

		res['action'] = 'csv';


		document.location = '?'+$.param(res);
	});
	$("input[type='tel']").mask("+7 (999) 999-99-99");
//Редактирование полей ввода логина и пароля
		var togler = {};

		$('#login input').each(function(){
			if($(this).attr('required')){
				togler[$(this).attr('id')] = 0;
			} else {
				togler[$(this).attr('id')] = 1;
			}
		});

	 $('#login input').on('blur', function(){
	 	validForm($(this));
	 });

	 $('#login input').on('focus', function(){
	 	notError($(this));
	 })

//Нажатие на кнопку Войти
	$('#submit').click(function(e){
		 if ($(this).hasClass('disabled')){
			e.preventDefault()
			$('#login input').each(function(){
				validForm($(this));
			});
		 } else {
			$(this).addClass('activity');
		 }
	 });

//Функция сброса класса с ошибкой для полей формы
	function notError(input) {
		input.removeClass('error').addClass('not_error');
	}
//Функция валидации формы
	function validForm(input) {

	  var submit = $('#submit');


	  var id = input.attr('id');
	  var val = input.val();
	  var min = input.attr('minlength')||0;
	  var max = input.attr('maxLenght')||val.length;
	  var pattern = new RegExp(input.attr('pattern')||'.*?');



	    if (input.attr('required')){
	      if(val.length >= min && val.length <= max && val != '' && pattern.test(val))
				{
					input.removeClass('error').addClass('not_error');
					togler[id] = 1;

	      }
				else
				{
					input.removeClass('not_error').addClass('error');
					input.next('.error-box').animate({'paddingLeft':'10px'},0)
	                                  .animate({'paddingLeft':'5px'},0);
					togler[id] = 0;

				}
	    } else {
	      if(val === '')
				{
					input.removeClass('error').addClass('not_error');
					togler[id] = 1;
	      }

	      else if(val.length >= min && val.length <= max && val != '' && pattern.test(val))
				{
					input.removeClass('error').addClass('not_error');
					togler[id] = 1;
	      }
				else
				{
					input.removeClass('not_error').addClass('error');
					input.next('.error-box').animate({'paddingLeft':'10px'},0)
	                                  .animate({'paddingLeft':'5px'},0);
					togler[id] = 0;
				}
		  }


	  var t=1;
		jQuery.each(togler, function(i, val){
	    t*=val;
	  });

		t!= 0?submit.removeClass('disabled'):submit.addClass('disabled');
	}

//Открытие модального окна по кнопке Редактировать
 $("body").on("click", ".change", function(){
 	$("#modal-main h2").text("Редактирование");
 	$("#modal-main #submit").text("Сохранить");
 });
//Открытие модального окна по кнопке Добавить
  $("body").on("click", ".content-header .blue-inline", function(){
 	$("#modal-main h2").text("Добавление");
 	$("#modal-main #submit").text("Добавить");
 });
//Закрытие модального окна по кнопке Сохранить/Добавить
	$('#modal-main #submit').click(function(){
		$('#modal-main').modal('hide');
	});
//Кнопка открытия/скрытия левого меню на мобильных
  $(".menu-toggler").click(function(){
  	$("nav.left-sidebar").toggleClass("open");
  	$(this).toggleClass("active");
  })


//Открываем окно с фильтрацией данных
	$("body").on('click', '.filter-btn', function(event){
		var filterOpen = $('.filter-block.active');
		$(this).next().toggleClass('active');
		filterOpen.removeClass('active');
	})

//Нажатие на кнопку очистки конкретного фильтра
	$("body").on('click', '.filter-info .clear', function(event){
		$(this).parent().remove();
		if(!$('.filter-info li').length) {
			$('.filter-info').hide();
		}
	})

//Сортировка по нажатию на заголовок тамблиц
	$("body").on('click', '.table th>span ', function(){
		var thSort = $('.table th.sort'),
			thThis = $(this).parent();
		if(!thThis.hasClass('nosort')){
			if(thThis.hasClass('sort')){
				var sortDirection = thThis.hasClass('up')?'down':'up';
				thThis.removeClass('up down');
				thThis.addClass(sortDirection);


			} else {
				thSort.removeClass('sort up down');
				thThis.addClass('sort up');
			}
		}
	});
});

//stas.js
function startRemovingImages()
{
	$(document).off('click.removingImages').on('click.removingImages', '.remove-image i',function(e)
	{
		e.preventDefault();
		var img = $(this).parent().parent();

		$.get(
			{
				url:'?action=remove_'+img.parent().attr('mysql')+'&id='+img.attr('image-id'),
				success:function()
				{
					img.hide('slow', function()
					{
						img.remove();
						$('.p-loading-container').remove();
					});
				},
				error:function()
				{
					alert("Не удалось удалить. Проверьте подключение к интернету.");
					$('.p-loading-container').remove();
				}
			});
		img.ploading({action:'show'});
	});
}

function initColorPicker()
{

	$('.color-picker-base').popover(
	{
		placement: function (context, source)
		{
			var position = $(source).position();

			if (position.left > 515)
			{
				return "left";
			}

			if (position.left < 515)
			{
				return "right";
			}

			if (position.top < 110)
			{
				return "bottom";
			}

			return "top";
		},
		trigger: "manual",
		container: "body",
		animation: false,
		html: true,
		content: '<div class="color-picker-popover-back text-center"><div class="color-picker-popover"></div><div style="margin-top:8px;"><a href="#" class="btn btn-cancel">Отмена</a><a href="#" class="btn btn-primary">Сохранить</a></div></div>'
	}).click(function(e)
	{
		$('.color-picker-base').popover('hide');
		var $t = $(this);

		$t.popover('show')


		e.stopPropagation();
	}).on('shown.bs.popover', function(e)
	{
		$t = $(e.target).find('.color-circle');
		$hex = $(e.target).find('.color-hex');
		$t.data('default-value', $t.data('value'));
		$('.popover-body').css('height', 'inherit');
		$.farbtastic('.color-picker-popover', function(color)
		{
			$t.data('value', color.substr(1));
			$t.css('background-color', color);
			$hex.text(color);
		}).setColor('#'+$t.data('value'));

		$(document).off('click.cancel-picking-color').on('click.cancel-picking-color', '.color-picker-popover-back .btn-cancel', function(e)
		{
			e.preventDefault();
			$t.data('value', $t.data('default-value'));
			$t.css('background-color', '#'+$t.data('value'));
			$hex.text('#'+$t.data('value'));
			$('.color-picker-base').popover('hide');
		});

		$(document).off('click.submit-picking-color').on('click.submit-picking-color', '.color-picker-popover-back .btn-primary', function(e)
		{
			e.preventDefault();
			e.stopPropagation();
			$('.color-picker-base').popover('hide');

			var static = $t.data('static');
			if(static && static==1)
			{
				$t.parent().find('input').val($t.data('value'));
			}
			else
			{
				var val = $t.data('value');
				var name = $t.data('name');
				var url = $t.data('url');
				var pk = $t.data('pk');


				$.ajax(url,
				{
					method: "POST",
					data:
					{
						name: name,
						value: val,
						pk: pk
					},
					success:function()
					{
					},
					error:function()
					{
						$t.css('background-color', '#'+$t.data('default-value'));
						$hex.text('#'+$t.data('default-value'));
						alert('Проверьте соединение с интернетом');
					},
				});
			}



		});

	});
}

function initCKEditor()
{
	$('.ckeditor').each(function()
	{

    try
    {
  		CKEDITOR.replace( $(this).attr('id'),
  		{
  			height: 500,
  			removeButtons:'Styles,Format,Last,Help,About',
  			contentsCss: [ 'https://cdn.ckeditor.com/4.9.0/full-all/contents.css', 'https://sdk.ckeditor.com/samples/assets/css/classic.css' ]
  		});
    } catch (e)
    {

    }
	});
}

function initSiema()
{
	if($('.siema').length>0)
	{
		document.mySiema = new Siema({'loop':true});
		const prev = document.querySelector('.siema-prev');
		const next = document.querySelector('.siema-next');

		prev.addEventListener('click', () => document.mySiema.prev());
		next.addEventListener('click', () => document.mySiema.next());


		document.mySiema.resizeHandler();
		setTimeout(function()
		{
			document.mySiema.resizeHandler();
		}, 300);

		$(window).on('resize', function()
		{
			document.mySiema.resizeHandler();
		});
	}
}


function initPresentationSwitch()
{
	$('.genesis-presentation-switch .segmented-control .list-group-item').on('click', function()
	{
		$(this).parent().find('.active').removeClass('active');
		$(this).addClass('active');
		var presentation = $(this).attr('presentation');

		$('.data-container')
			.removeClass('genesis-presentation-table')
			.removeClass('genesis-presentation-list')
			.removeClass('genesis-presentation-cards')
			.addClass('genesis-presentation-'+presentation);

	});

}

$(document).ready(function()
{
	initSiema();
	initPresentationSwitch();

	$(document).on('click', '.js-extra-filters', function()
	{
		$('.filter').popover('hide');
	});

	if($('#tableMain .sortable-handle').length>0)
	{
		$('#tableMain .genesis-tbody').sortable(
		{
			handle:'.sortable-handle',
			helper: function(e, tr)
			{
				var $originals = tr.children();
				var $helper = tr.clone();
				$helper.css('background', '#ffffff');
				$helper.css('opacity', '0.7');
				$helper.children().each(function(index)
				{

					$(this).width($originals.eq(index).outerWidth());
				});
				return $helper;
			},
			update: function(event, ui)
			{
				var list = [];
				$('#tableMain .genesis-item').each(function()
				{
					var id = $(this).attr('pk');
					if(id)
					{
						list.push(id);
					}
				});
				list = JSON.stringify(list);

				$.ajax('?',
				{
					'data':
					{
						'action':'reorder',
						'genesis_ids_in_order':list
					},
					'success': function(str)
					{
						try
						{
							var resp = JSON.parse(str);
							if(resp.status!=0)
							{
								alert("Не удалось сохранить сортировку. Страница будет перезагружена.");
								document.location.reload(); //тут нужно обновить страницу, потому что интерфейс рассинхронизировался с серваком
							}
						}
						catch(e)
						{
							alert("Не удалось сохранить сортировку. Страница будет перезагружена.");
							document.location.reload(); //тут нужно обновить страницу, потому что интерфейс рассинхронизировался с серваком
						}
					},
					'error': function()
					{
						alert("Не удалось сохранить сортировку. Страница будет перезагружена.");
						document.location.reload();
					}
				});

			}
		});
	}



	$(document).click(function()
	{
		$('.color-picker-base').popover('hide');
	});

	$(document).on('click', '.color-picker-popover', function(e)
	{
      e.stopPropagation();
	});



	$(document).on('keypress', 'input[type=number]',function(e){
	  var flag = e.metaKey || // cmd/ctrl
	    e.which <= 0 || // arrow keys
	    e.which == 8 || // delete key
	    /[0-9,.]/.test(String.fromCharCode(e.which)); // numbers

      return flag && ($(this).val().length<10 || e.which == 8  || e.which <= 0 );
	});
	startRemovingImages();

    initCKEditor();

	$('input.daterange').daterangepicker(
	{
		 autoUpdateInput: false,
	"locale":
	{
		"format": "DD.MM.YYYY",
		"separator": " – ",
		"applyLabel": "Ок",
		"cancelLabel": "Отмена",
		"fromLabel": "С",
		"toLabel": "По",
		"customRangeLabel": "Custom",
		"daysOfWeek":
		[
			"Вс",
			"Пн",
			"Вт",
			"Ср",
			"Чт",
			"Пт",
			"Сб"
		],
		"monthNames":
		[
			"Январь",
			"Февраль",
			"Март",
			"Апрель",
			"Май",
			"Июнь",
			"Июль",
			"Август",
			"Сентябрь",
			"Октябрь",
			"Ноябрь",
			"Декабрь"
		],
		"firstDay": 1
	}
	});

	$('input.daterange').on('apply.daterangepicker', function(ev, picker)
	{
		picker = $(ev.target).data().daterangepicker;
		$(this).val(picker.startDate.format('DD.MM.YYYY') + ' – ' + picker.endDate.format('DD.MM.YYYY'));
	});

 	$('input.daterange').on('cancel.daterangepicker', function(ev, picker)
	{
		$(this).val('');
 	});


	$('.dz-message').remove();
	$('select:not(.ajax-select2)').select2();


	$('.multiimage-container').on('mousedown', function()
	{
		$(this).css('min-height', $(this).height());
	}).on('mouseup', function()
	{
		$(this).css('min-height', '');
	});

	$('.multiimage-container').sortable(
	{
		tolerance: "intersect",
		items:".uploaded-image",
		stop: function (event, ui)
		{
			var $this = $(this);
			//$this.ploading({action:'show'});
			var items = [];
			$this.find('.uploaded-image').each(function()
			{
				items.push($(this).attr('image-id'));
			});
			$.get('?action=sort_'+$(this).attr('mysql')+'&items='+JSON.stringify(items)).success(function()
			{
			//	$this.ploading({action:'hide'});
				$('.p-loading-container').remove();
			}).error(function()
			{
			//	$this.ploading({action:'hide'});
				$('.p-loading-container').remove();
			  $this.sortable('cancel');
				alert("Не удалось сохранить изменение. Проверьте подключение к интернету.");
			});
		}
	});
	$('a.sort').click(function(e)
	{
		e.preventDefault();

		var res = {};
		$('input.filter[type=hidden]').each(function()
		{
			res[$(this).attr('name')] = $(this).val();
		});
		document.location = $(this).attr('href')+'&'+$.param(res);
	});

	$('.search-form').submit(function(e)
	{
		e.preventDefault();

		var res = {};
		$('input.filter[type=hidden]').each(function()
		{
			res[$(this).attr('name')] = $(this).val();
		});


		for (var k in JS_REQUEST)
		{
      if(k!='page')
      {
		    res[k] = JS_REQUEST[k];
      }
		}

		res['srch-term'] = $('#srch-term').val();


		document.location = '?'+$.param(res);

	});

	$('.remove-tag').on('click', function()
	{
		var cur_filter_name = $(this).parent().find('input').attr('name');
		$(this).parent().remove();

		var res = {};

		for (var k in JS_REQUEST)
		{
			if(k!=cur_filter_name)
			{
				res[k] = JS_REQUEST[k];
			}
		}

		$('input.filter[type=hidden]').each(function()
		{
			res[$(this).attr('name')] = $(this).val();
		});

		res['srch-term'] = JS_REQUEST['srch-term'];
		res['sort_by'] = JS_REQUEST['sort_by'];
		res['sort_order'] = JS_REQUEST['sort_order'];

		document.location = '?'+$.param(res);


	});

	$(document).keypress(function(e)
	{
		if(e.which==27)
		{
			$('.filter').popover('hide');
			$('.link-image').popover('hide');
		}
	});

	$('body').on('hidden.bs.popover', function (e)
	{
	    $(e.target).data("bs.popover").inState = { click: false, hover: false, focus: false }
	});

	$('.link-image').popover({html:true});

	$('.filter').popover({html:true}).on('shown.bs.popover', function()
	{
		$('select:not(.ajax-select2)').select2();
		$('.popover-content input[type=text]').focus();
		$('input.daterange').daterangepicker(
		{
			 autoUpdateInput: false,
		"locale":
		{
			"format": "DD.MM.YYYY",
			"separator": " – ",
			"applyLabel": "Ок",
			"cancelLabel": "Отмена",
			"fromLabel": "С",
			"toLabel": "По",
			"customRangeLabel": "Custom",
			"daysOfWeek":
			[
				"Вс",
				"Пн",
				"Вт",
				"Ср",
				"Чт",
				"Пт",
				"Сб"
			],
			"monthNames":
			[
				"Январь",
				"Февраль",
				"Март",
				"Апрель",
				"Май",
				"Июнь",
				"Июль",
				"Август",
				"Сентябрь",
				"Октябрь",
				"Ноябрь",
				"Декабрь"
			],
			"firstDay": 1
		}
		});

		$('input.daterange').on('apply.daterangepicker', function(ev, picker)
		{
			picker = $(ev.target).data().daterangepicker;
			$(this).val(picker.startDate.format('DD.MM.YYYY') + ' – ' + picker.endDate.format('DD.MM.YYYY'));
		});

		$('input.daterange').on('cancel.daterangepicker', function(ev, picker)
		{
			$(this).val('');
		});
	});



	$(document).on('keypress', '.filter-text', function(e)
	{
		if(e.which == 13)
		{
			$(this).parent().find('.add-filter').click();
		}
	});

	$(document).on('click', '.add-filter', function()
	{

		var res = {};

		for (var k in JS_REQUEST)
		{
			res[k] = JS_REQUEST[k];
		}

		var p = $(this).parent().parent();

		$('input.filter[type=hidden]').each(function()
		{
			res[$(this).attr('name')] = $(this).val();
		});

		var texts = p.find('.filter-text');
		if(texts.length>0)
		{
			texts.each(function(i, text)
			{
				text = $(text);
				var k = text.attr('name');
				var v = text.val();
				res[k] = v;
			});

		}

		var checkboxes = p.find('.filter-checkbox');
		if(checkboxes.length>0)
		{
			checkboxes.each(function(i, checkbox)
			{
				checkbox = $(checkbox);
				var k = checkbox.attr('name');
				var v = checkbox.prop('checked')?1:0;
				res[k] = v;
			});
		}

		var tags = p.find('.filter-tags');
		if(tags.length>0)
		{
			tags.each(function(i, tag)
			{
				tag = $(tag);
				var k = tag.parent().find('input[type=hidden]').attr('name');
				var v = tag.textext()[0].tags()._formData.join(", ");
				res[k] = v;
			});
		}


		var nums = p.find('.filter-number-from');
		if(nums.length>0)
		{
			nums.each(function(i, num)
			{
				num = $(num);
				var k = num.attr('name');
				var v = num.val();
				res[k] = v;
			});
		}

		var nums = p.find('.filter-number-to');
		if(nums.length>0)
		{
			nums.each(function(i, num)
			{
				num = $(num);
				var k = num.attr('name');
				var v = num.val();
				res[k] = v;
			});
		}

		var sels = p.find('.filter-select');
		if(sels.length>0)
		{
			sels.each(function(i, sel)
			{
				sel = $(sel);
				var k = sel.attr('name');
				var v = sel.val();
				res[k] = v;
			});
		}

		var dts = p.find('.filter-date-range');
		if(dts.length>0)
		{
			dts.each(function(i, dt)
			{
				dt = $(dt);
				if(dt.val()!="")
				{
					var k = dt.attr('name')+'_from';
					var v = dt.data('daterangepicker').startDate.format('YYYY-MM-DD');
					res[k] = v;

					k = dt.attr('name')+'_to';
					v = dt.data('daterangepicker').endDate.format('YYYY-MM-DD');
					res[k] = v;
				}
			});


		}

		res['srch-term'] = JS_REQUEST['srch-term'];
		res['sort_by'] = JS_REQUEST['sort_by'];
		res['sort_order'] = JS_REQUEST['sort_order'];

		document.location = '?'+$.param(res);

	});


	$("#edit_page_save").off("click").on("click", function()
	{
		$(this).parent().parent().submit();
	});
	$(".add_button").on("click", function()
	{
		globalLoading(true);
		$.get("",
		{
			"action":"create"
		},
		function(str)
		{
			globalLoading(false);
			$('.filter').popover('hide');
			$('.link-image').popover('hide');
			$("#create_modal").find(".modal-body").html(str);
			$("#create_modal").modal("show").on('shown.bs.modal', function()
      		{
				$('input[data-inp=phone]').inputmask("+7 (###) ###-##-##");
		  		var inp = $("#create_modal input[type=text],[type=password],[type=number]");
		  		if(inp.length>0)
		  		{
					inp .first()[0].focus();
				}

				initAjaxSelectField();
				setTimeout(function(){initAjaxSelectField();}, 10);
      	});

			$('.not-editable').hide();
			$("#create_save").off("click").on("click", function()
			{
				$(this).prop('disabled', true).css('pointer-events', 'none');
				$(this).parent().parent().find('input[type=checkbox]:not(checked)').each(function()
				{
					if(!$(this).prop('checked'))
					{
						$(this).attr('value', 0);
						$(this).attr('type', 'hidden');
					}
				});
				$(this).parent().parent().find("form").submit();
			});
			$('select:not(.ajax-select2)').select2();

			$('.datepicker').each(function()
			{
				var $t = $(this);
				$t.datetimepicker({format: $t.attr('data-format'), timepicker:$t.attr('data-timepicker')==1});
			});
			// $('.datepicker').combodate(
			// {
			// 	dateFormat: 'DD.MM.YYYY HH:mm',
			// 	format: 'YYYY-MM-DD HH:mm:00',
			// 	combodate:
			// 	{
			// 		minYear: 2000,
			// 		maxYear:2030
			// 	},
			// 	minYear: 2000,
			// 	maxYear:2030
			// });
			$('input[data-inp=phone]').inputmask("+7 (###) ###-##-##");
			initCKEditor();
			initSegmentedControls();
			initCheckboxes();
			initColorPicker();
		});
	});


	$(".table-clickable-page .genesis-item-property, .table-clickable > tbody > tr > td").on("click", function(e)
	{
		if(($(e.target).hasClass('genesis-item-property') || e.target.tagName=="TD") && $(e.target).parent().parent().parent().hasClass('table-clickable-page'))
		{
			var params = "";
			for (var k in JS_REQUEST)
			{
				params+= k+"="+encodeURIComponent(JS_REQUEST[k])+"&";
			}
			document.location = '?action=edit_page&genesis_edit_id='+$(this).parent().attr("pk")+"&"+params;
		}
	}).children().click(function(e)
	{
			e.stopPropagation();
	});

  $('.edit_btn').on("click", function(e)
  {
	  e.preventDefault();
	  $(this).parent().parent().find('.genesis-item-property').first().click();
  });

  	function openEditModal(id)
	{
		document.current_row_id = id;
		globalLoading(true);
		$.get("",
		{
			"action":"edit",
			"genesis_edit_id":document.current_row_id
		},
		function(str)
		{
			globalLoading(false);
			$('.filter').popover('hide');
			$('.link-image').popover('hide');
			$("#edit_modal").find(".modal-body").html(str);
			$('.not-editable').show();

			$('#edit_modal').off('shown.bs.modal').on('shown.bs.modal', function ()
			{
				initCKEditor();
				initColorPicker();
				initSegmentedControls();
				initCheckboxes();


				$('select:not(.ajax-select2)').select2();

				$('.datepicker').each(function()
				{
					var $t = $(this);
					$t.datetimepicker({format: $t.attr('data-format'), timepicker:$t.attr('data-timepicker')==1});
				});
				$('input[data-inp=phone]').inputmask("+7 (###) ###-##-##");

				initAjaxSelectField();
				setTimeout(function(){initAjaxSelectField();}, 10);

			});
			$("#edit_modal").modal("show");


			$('input[data-inp=phone]').inputmask("+7 (###) ###-##-##");

			var inp = $("#edit_modal input[type=text],[type=password],[type=number]");
			if(inp.length>0)
			{
			  inp.first()[0].focus();
			}
		});

		$("#edit_save").off("click").on("click", function()
		{
			$(this).prop('disabled', true).css('pointer-events', 'none');
			$(this).parent().parent().find('input[type=checkbox]:not(checked)').each(function()
			{
				if(!$(this).prop('checked'))
				{
					$(this).attr('value', 0);
					$(this).attr('type', 'hidden');
				}
			});
			window.location.hash = '/';
			$(this).parent().parent().find("form").submit();
		});

		$('#edit_modal').off('hidden.bs.modal').on('hidden.bs.modal', function ()
		{
			window.location.hash = '/';
		});







	}

	$(".table-clickable .genesis-item-property, .table-clickable > tbody > tr > td").on("click", function(e)
	{
		if(($(e.target).hasClass('genesis-item-property') || e.target.tagName=="TD") && $(e.target).parent().parent().parent().hasClass('table-clickable'))
		{
			id = $(this).parent().attr("pk");
			// openEditModal(id);
			window.location.hash = '/'+id;
		}

	}).children().click(function(e)
	{
			e.stopPropagation();
	});

	id = window.location.hash.replace(/^#\//, '');
	if(id)
	{
		openEditModal(id);
	}

	$(window).bind('hashchange', function()
	{
		$('#edit_modal').modal('hide');
		id = window.location.hash.replace(/^#\//, '');
		if(id)
		{
			openEditModal(id);
		}
	});
	$(".delete_btn").click(function(e)
	{
		e.preventDefault();
		if(confirm("Удалить"))
		{
			var btn = $(this);
			$.get("",
			{
				"action":"delete",
				"id": btn.parent().parent().attr("pk")
			}, function(resp)
			{
				if(resp==1)
				{
					btn.parent().parent().hide("slow", function()
				{
					btn.trigger("removed");
				});

					var cnt = parseInt($('.cnt-number-span').text());
					cnt--;
					$('.cnt-number-span').text(cnt);

				}
				else
				{
					alert('Строку удалить нельзя: c ней связаны другие объекты.');
				}
			});
		}
	});
});

var md5 = function(s){function L(k,d){return(k<<d)|(k>>>(32-d))}function K(G,k){var I,d,F,H,x;F=(G&2147483648);H=(k&2147483648);I=(G&1073741824);d=(k&1073741824);x=(G&1073741823)+(k&1073741823);if(I&d){return(x^2147483648^F^H)}if(I|d){if(x&1073741824){return(x^3221225472^F^H)}else{return(x^1073741824^F^H)}}else{return(x^F^H)}}function r(d,F,k){return(d&F)|((~d)&k)}function q(d,F,k){return(d&k)|(F&(~k))}function p(d,F,k){return(d^F^k)}function n(d,F,k){return(F^(d|(~k)))}function u(G,F,aa,Z,k,H,I){G=K(G,K(K(r(F,aa,Z),k),I));return K(L(G,H),F)}function f(G,F,aa,Z,k,H,I){G=K(G,K(K(q(F,aa,Z),k),I));return K(L(G,H),F)}function D(G,F,aa,Z,k,H,I){G=K(G,K(K(p(F,aa,Z),k),I));return K(L(G,H),F)}function t(G,F,aa,Z,k,H,I){G=K(G,K(K(n(F,aa,Z),k),I));return K(L(G,H),F)}function e(G){var Z;var F=G.length;var x=F+8;var k=(x-(x%64))/64;var I=(k+1)*16;var aa=Array(I-1);var d=0;var H=0;while(H<F){Z=(H-(H%4))/4;d=(H%4)*8;aa[Z]=(aa[Z]| (G.charCodeAt(H)<<d));H++}Z=(H-(H%4))/4;d=(H%4)*8;aa[Z]=aa[Z]|(128<<d);aa[I-2]=F<<3;aa[I-1]=F>>>29;return aa}function B(x){var k="",F="",G,d;for(d=0;d<=3;d++){G=(x>>>(d*8))&255;F="0"+G.toString(16);k=k+F.substr(F.length-2,2)}return k}function J(k){k=k.replace(/rn/g,"n");var d="";for(var F=0;F<k.length;F++){var x=k.charCodeAt(F);if(x<128){d+=String.fromCharCode(x)}else{if((x>127)&&(x<2048)){d+=String.fromCharCode((x>>6)|192);d+=String.fromCharCode((x&63)|128)}else{d+=String.fromCharCode((x>>12)|224);d+=String.fromCharCode(((x>>6)&63)|128);d+=String.fromCharCode((x&63)|128)}}}return d}var C=Array();var P,h,E,v,g,Y,X,W,V;var S=7,Q=12,N=17,M=22;var A=5,z=9,y=14,w=20;var o=4,m=11,l=16,j=23;var U=6,T=10,R=15,O=21;s=J(s);C=e(s);Y=1732584193;X=4023233417;W=2562383102;V=271733878;for(P=0;P<C.length;P+=16){h=Y;E=X;v=W;g=V;Y=u(Y,X,W,V,C[P+0],S,3614090360);V=u(V,Y,X,W,C[P+1],Q,3905402710);W=u(W,V,Y,X,C[P+2],N,606105819);X=u(X,W,V,Y,C[P+3],M,3250441966);Y=u(Y,X,W,V,C[P+4],S,4118548399);V=u(V,Y,X,W,C[P+5],Q,1200080426);W=u(W,V,Y,X,C[P+6],N,2821735955);X=u(X,W,V,Y,C[P+7],M,4249261313);Y=u(Y,X,W,V,C[P+8],S,1770035416);V=u(V,Y,X,W,C[P+9],Q,2336552879);W=u(W,V,Y,X,C[P+10],N,4294925233);X=u(X,W,V,Y,C[P+11],M,2304563134);Y=u(Y,X,W,V,C[P+12],S,1804603682);V=u(V,Y,X,W,C[P+13],Q,4254626195);W=u(W,V,Y,X,C[P+14],N,2792965006);X=u(X,W,V,Y,C[P+15],M,1236535329);Y=f(Y,X,W,V,C[P+1],A,4129170786);V=f(V,Y,X,W,C[P+6],z,3225465664);W=f(W,V,Y,X,C[P+11],y,643717713);X=f(X,W,V,Y,C[P+0],w,3921069994);Y=f(Y,X,W,V,C[P+5],A,3593408605);V=f(V,Y,X,W,C[P+10],z,38016083);W=f(W,V,Y,X,C[P+15],y,3634488961);X=f(X,W,V,Y,C[P+4],w,3889429448);Y=f(Y,X,W,V,C[P+9],A,568446438);V=f(V,Y,X,W,C[P+14],z,3275163606);W=f(W,V,Y,X,C[P+3],y,4107603335);X=f(X,W,V,Y,C[P+8],w,1163531501);Y=f(Y,X,W,V,C[P+13],A,2850285829);V=f(V,Y,X,W,C[P+2],z,4243563512);W=f(W,V,Y,X,C[P+7],y,1735328473);X=f(X,W,V,Y,C[P+12],w,2368359562);Y=D(Y,X,W,V,C[P+5],o,4294588738);V=D(V,Y,X,W,C[P+8],m,2272392833);W=D(W,V,Y,X,C[P+11],l,1839030562);X=D(X,W,V,Y,C[P+14],j,4259657740);Y=D(Y,X,W,V,C[P+1],o,2763975236);V=D(V,Y,X,W,C[P+4],m,1272893353);W=D(W,V,Y,X,C[P+7],l,4139469664);X=D(X,W,V,Y,C[P+10],j,3200236656);Y=D(Y,X,W,V,C[P+13],o,681279174);V=D(V,Y,X,W,C[P+0],m,3936430074);W=D(W,V,Y,X,C[P+3],l,3572445317);X=D(X,W,V,Y,C[P+6],j,76029189);Y=D(Y,X,W,V,C[P+9],o,3654602809);V=D(V,Y,X,W,C[P+12],m,3873151461);W=D(W,V,Y,X,C[P+15],l,530742520);X=D(X,W,V,Y,C[P+2],j,3299628645);Y=t(Y,X,W,V,C[P+0],U,4096336452);V=t(V,Y,X,W,C[P+7],T,1126891415);W=t(W,V,Y,X,C[P+14],R,2878612391);X=t(X,W,V,Y,C[P+5],O,4237533241);Y=t(Y,X,W,V,C[P+12],U,1700485571);V=t(V,Y,X,W,C[P+3],T,2399980690);W=t(W,V,Y,X,C[P+10],R,4293915773);X=t(X,W,V,Y,C[P+1],O,2240044497);Y=t(Y,X,W,V,C[P+8],U,1873313359);V=t(V,Y,X,W,C[P+15],T,4264355552);W=t(W,V,Y,X,C[P+6],R,2734768916);X=t(X,W,V,Y,C[P+13],O,1309151649);Y=t(Y,X,W,V,C[P+4],U,4149444226);V=t(V,Y,X,W,C[P+11],T,3174756917);W=t(W,V,Y,X,C[P+2],R,718787259);X=t(X,W,V,Y,C[P+9],O,3951481745);Y=K(Y,h);X=K(X,E);W=K(W,v);V=K(V,g)}var i=B(Y)+B(X)+B(W)+B(V);return i.toLowerCase()};

  $(function()
  {
	  $(window).on('keyup', function(e)
	  {
		  if(e.ctrlKey && e.shiftKey)
		  {
			// Up: 38
			// Down: 40
			// Right: 39
			// Left: 37
				var flag = false;
			  	if (e.keyCode == 38)
			  	{
				  	document.editableY--;
					flag = true;
			  	}
			  	if (e.keyCode == 40)
			  	{
				  	document.editableY++;
					flag = true;
			  	}
			  	if (e.keyCode == 37)
			  	{
				  	document.editableX--;
					flag = true;
			  	}
			  	if (e.keyCode == 39)
			  	{
				  	document.editableX++;
					flag = true;
			  	}

				if(flag)
				{
					$('.editable').editable('hide');
					var tr = $('#tableMain .genesis-item').get(document.editableY);
					if(tr)
					{
						var td = $(tr).find('.genesis-item-property').get(document.editableX);
						if(td)
						{
							var ed = $(td).find('.editable');
							if(ed)
							{
								e.preventDefault();
								$('.editable').editable('hide');
								$(ed).editable('show');
								setTimeout(function(){ $('.editableform input').select(); }, 100);

							}
						}
					}
				}
		  }
	  });
	  document.editableX = 0;
	  document.editableY = 0;
	  $('.editable').click(function()
  	  {
	  	$('.editable').editable('hide');
		document.editableX = $(this).parent().parent().children().index($(this).parent());
		document.editableY = $(this).parent().parent().parent().children().index($(this).parent().parent())+1;

	  });

	  $('.editable').on('shown', function(e, editable)
	  {
		  setTimeout(function(){editable.input.$input.focus()}, 10);
	  });
		$('.editable[data-inp=tags]').editable({'placement':'bottom'});
    $('.editable[data-inp=text]').editable({'placement':'bottom'});
    $('.editable[data-inp=select]').editable({'placement':'bottom', 'cssclass' : 'selectClass'}).on('shown', function(e, editable)
		{
			setTimeout(function()
			{
				$('select:not(.ajax-select2)').select2()
				var txt = $(e.target).text();
				editable.input.$input.find('option').each(function(i, o)
				{
						if($(o).text()==txt)
						{
							editable.input.$input.val($(o).val()).trigger('change');
						}
				});

			}, 10);
		});

		$('.editable[data-inp=select-ajax]').editable({'placement':'bottom', 'cssclass' : 'selectClass'}).on('shown', function(e, editable)
			{
				var $t = $(this);
				setTimeout(function()
				{
					$(editable.input.$input).select2(
					{
						ajax:
						{
							url: '?action='+$(editable.$element).attr('data-action'),
							dataType: 'json'
						},
						width:'200px'
					});

					$t.on('save', function(e, params)
					{
						alert('Saved value: ' + params.newValue);
					});
					$(document).off('click', '.select2-container li').on('click', '.select2-container li', function (e)
					{
						var sel_id = $(this).attr('data-genesis-value');
						$(editable.$element).attr('data-selected-value', sel_id);
						$(editable.$element).attr('data-selected-value-text', $(this).text());
						$('.select2-selection__rendered').text($(this).text());
						$(editable.input.$input).select2('close');
					});

					$('.editable-submit').off('click').on('click', function(e)
					{
						e.preventDefault();

						var new_val = $(editable.$element).attr('data-selected-value');
						var new_val_text = $(editable.$element).attr('data-selected-value-text');
						$('.editable-container').ploading({action:'show'});
						$.ajax($t.attr('data-url'),
						{
							data:
							{
								pk: $t.attr('data-pk'),
								name: $t.attr('data-name'),
								value: new_val
							},
							success: function()
							{
								editable.$element.text(new_val_text);
								$('.editable-container').ploading({action:'hide'});
								$t.editable('hide');
							},
							error: function()
							{
								$('.editable-container').ploading({action:'hide'});
								alert('Ошибка связи с сервером');
							}

						});
					});

				}, 10);
			});

    $('.editable[data-inp=textarea]').editable({'type':'textarea', 'placement':'bottom'});
    $('.editable[data-inp=number]').editable({'type':'number', 'placement':'bottom'});
		$('.editable[data-inp=decimal]').editable({'type':'number', 'placement':'bottom'}).on('shown', function(e, editable)
		{
			editable.input.$input.attr('step', 0.01)
		});

    $('.editable[data-inp=date]').editable({'type':'text', 'placement':'bottom'}).on('shown', function(e, editable)
    {
      editable.input.$input.datetimepicker({format: (editable.$element).attr('data-format'), timepicker:(editable.$element).attr('data-timepicker')==1});
    });

    $('.editable[data-inp=phone]').editable({'placement':'bottom'}).on('shown', function(e, editable)
    {
      var phone = editable.input.$input;
      phone.inputmask("+7 (###) ###-##-##");
    });





    $('input[data-inp=phone]').inputmask("+7 (###) ###-##-##");


  	$('.editable[data-inp=video]').editable(
    {
      validate:function(value)
      {
        if(getYoutubeCode(value)=='<span style="color:red;">Видео не найдено</span>')
				{
					return "Видео не найдено";
				}
      },
			success:function(resp, value)
			{
				$(this).parent().find('.video-wrap').html(getYoutubeCode(value));
			}
    });

    $('.editable[data-inp=pass]').html("Сменить пароль").editable(
    {
      validate:function(value)
      {
		if($(this).data('hash')==1)
		{
			return {'newValue': value};
		}
		else
		{
        	return {'newValue': md5(value)};
		}
      },
      success:function(value)
      {
        return {'newValue': ""};
      },
      value:""
    });


    $('editable').click(function(e){
        e.stopPropagation();
    });

		$('.datepicker').each(function()
		{
			var $t = $(this);
			$t.datetimepicker({format: $t.attr('data-format'), timepicker:$t.attr('data-timepicker')==1});
		});
		//format: (editable.$element).attr('data-format'), timepicker:(editable.$element).attr('data-timepicker')==1
    // $('.datepicker').combodate(
    // {
    //   dateFormat: 'DD.MM.YYYY HH:mm',
    //   format: 'YYYY-MM-DD HH:mm:00',
    //   combodate:
    //   {
    //     minYear: 2000,
    //     maxYear:2030
    //   },
    //   minYear: 2000,
    //   maxYear:2030
    // });

    initSegmentedControls();
		initCheckboxes();
		initBackButton();
		initColorPicker();

  });

	function initBackButton()
	{
		var rules = Cookies.get('back-rules');
		if (typeof rules == 'undefined')
		{
			rules = [];
		}

		if (typeof rules == 'string')
		{
			rules = JSON.parse(rules);
		}

		var url = window.location.pathname;
		var filename = url.substring(url.lastIndexOf('/')+1);
		var apliable_rules = rules.filter(r => r.dst==filename);

		if (apliable_rules.length>0)
		{
			$('.back-btn').show();
			$('.back-btn').attr('href', apliable_rules[0].src);
		}

		$('.btn-genesis').off('click.backButton', ).on('click.backButton', function()
		{



			var dst = $(this).attr('href').split('?')[0];

			rules = rules.filter(r => r.dst!=dst);
			rules.push(
				{
					'src': document.location.href,
					'dst': dst
				});
			Cookies.set('back-rules', rules);
		});
	}

	function initCheckboxes()
	{
		$('.ajax-checkbox').on('click', function(e)
		{
			var $check = $(this);
			$check.attr("disabled", true);

			var name = $check.data('name');
			var pk = $check.data('pk');
			var url = $check.data('url');

			$.ajax(
				{
					url:url,
					data:
					{
						pk:pk,
						name:name,
						value:$check.prop("checked")?1:0
					},
					success:function()
					{
						$check.removeAttr("disabled");
					},
					error:function()
					{
						$check.prop("checked", !$check.prop("checked"));
						$check.removeAttr("disabled");
						alert("Не удалось сохранить. Проверьте соединение с интернетом.");
					}
				});
			});
		}

	function initSegmentedControls()
	{

    $('div.segmented-control-edit a').not('.genesis-presentation-switch a').on('click', function(e)
    {
		e.preventDefault();
      var $t = $(this);
      $t.parent().parent().find(' div.segmented-control a').each(function(i,e)
      {
          $(e).removeClass('active');
      });

      $t.addClass('active');
      $t.find('input').prop('checked',true);
    });



		$('div.segmented-control a').not('.segmented-control-edit a').not('.genesis-presentation-switch a').on('click', function(e)
		{
			e.preventDefault();
				var $t = $(this);
				$(this).ploading({action:'show'});
				$.ajax(
					{
						url:"engine/ajax.php?action=editable",
						method:"POST",
						data:
						{
							table:$(this).parent().attr('table'),
							name:$(this).parent().attr('name'),
							pk:$(this).parent().attr('pk'),
							value:$(this).find('input').attr('value')
						},
						success:function ()
						{
							$t.parent().parent().find('div.segmented-control a').each(function(i,e)
							{
									$(e).removeClass('active');
							});

							$t.addClass('active');
							$t.find('input').prop('checked',true);
							$t.ploading({action:'hide'});
						},
						error:function ()
						{
							$t.ploading({action:'hide'});
						}
					});

		    return false;
		});
	}

	window.onload = function()
	{


    $(".submenu-caption").click(
        function(e)
		{
            e.preventDefault();
            var submenu = $(this).parent().next('.submenu');
            if(!submenu){return;}
			var caret = $(this).children(".fas");
			if(submenu.is(':hidden'))
			{
				caret.addClass('open-caret');
			}
			else
			{
				caret.removeClass('open-caret');
			}
			submenu.slideToggle('slow');





            return false;
        }
    );
};

function initAjaxSelectField()
{
	$('.ajax-select2').each(function()
	{
		var $t = $(this);
		$(this).select2(
		{
			ajax:
			{
				url: '?action='+$(this).attr('data-action'),
				dataType: 'json',
				dropdownParent: $(this).closest('.modal')
			}
		});

		$('.select2-selection__rendered').text($t.attr('data-initial-text'));

		$(document).off('click', '.select2-container li').on('click', '.select2-container li', function (e)
		{
			var sel_id = $(this).attr('data-genesis-value');
			var hid = $('[name='+$t.attr('data-hidden-input')+']');
			hid.val(sel_id);
			$('.select2-selection__rendered').text($(this).text());
			$t.select2('close');
		});
	});

}


