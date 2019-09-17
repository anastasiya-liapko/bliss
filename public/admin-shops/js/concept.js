
$(function() {

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