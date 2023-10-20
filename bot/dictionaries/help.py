from enums.UserRole import UserRole

user_helps = {
    UserRole.USER.value[0]: {
        'general-requirements':
            {
                'title': '___Общие требования___',
                'description':
                    '      1. Осмотр объекта страхования заключается в проведении фото-видеосъемки клиентом согласно требованиям страховщика в зависимости от объекта страхования.\n\n'
                    '      2. Фото и видео объекта направляются страховщику на проверку, результатом которой может быть согласование или отправка на корректировку в связи с уточняющими вопросами или исправлением фото-видео материалов.\n\n'
                    '      3. По результатам согласования осмотра формируется акт осмотра, который клиенту необходимо согласовать (подписать)\n\n'
                    '      4. Акт прикладывается к комплекту документов для заключения договора страхования.\n\n'
            },

        'general-photo-requirements':
            {
                'title': '___Общие требования к фото___',
                'description':
                    '\n      ___ВАЖНО___. Фото для осмотра должны быть сделаны только с помощью ___телефона___. '
                    'Для корректной обработки фото Вам необходимо предоставить доступ к геолокации ___в приложении "Камера"___. Это можно сделать в разделе "Настройки" камеры.\n\n'
                    '       Для того, чтобы предоставить доступ к данным местоположения, необходимо сделать следующее:\n'
                    '___"Камера" -> "Настройки" -> "Сохранять место съемки"("Тег местоположения" и другие, в зависимости от модели телефона).___\n\n'
                    '       ___Фотоматериалы также должны удовлетворять следующим требованиям:___\n\n'
                    '- Редактирование фотоматериалов объекта страхования ___строго запрещено.___\n'
                    '- Разрешение фото должно быть ___не ниже 1600x1200 пикселей.___'

            },

        'transport-requirements':
            {'title': '___Требования к осмотру транспортного средства___',
             'description':
                 '- Транспортное средство должно быть в чистом виде\n'
                 '- Не принимаются фото транспортного средства в разобранном виде или в процессе ремонта (установки противоугонных устройств и т.п.)\n'
                 '- В процессе проведения осмотра необходимо сделать следующие фотографии:\n'
                 '      1. Фото VIN-номера на металле – минимум 1 фото;\n'
                 '      2. Фото транспортного средства снаружи – минимум 8 фото: с 4-х сторон + с 4-х углов (допускается больше при необходимости);\n'
                 '      3. Фото лобового стекла – минимум 1 фото;\n'
                 '      4. Фото маркировки лобового стекла – 1 фото;\n'
                 '      5. Фото колеса в сборе – минимум 1 фото (должны читаться размер и производитель шины);\n'
                 '      6. Фото показаний одометра (пробег) – 1 фото;\n'
                 '      7. Фото салона – минимум 2 фото: передняя часть салона с приборной панелью + задняя часть салона;\n'
                 '      8. Фото всех повреждений (при наличии) – неограниченное количество фото;\n'
                 '      9. Фото штатных ключей + ключей/брелоков/меток от дополнительных противоугонных устройств.\n'},

        'country-house-requirements':
            {'title': '___Требования к осмотру загородного дома___',
             'description':
                 'В процессе проведения осмотра необходимо сделать следующие фотографии:\n'
                 '      - **Общий вид участка**. Несколько ракурсов участка для определения расстояний между объектами страхования, ограждением (забором), соседними сооружениями/зданиями, подъездными дорогами, объектами повышенного риска (стройка, водоемы и т.п.);\n'
                 '      - **Наружные инженерные коммуникации и сооружения**. Электроснабжения, водоснабжения, водоотведения, теплоснабжения, такие как: септик, эл.станция, трансформатор, распределительный щит, скважина, колодец, насос и т.п.\n'
                 '      - **Фасады строений**. Каждое строение с 4-х сторон, элементы внешней отделки фасадов, кровлю, фундамент.\n'
                 '      - **Механическую защиту окон и дверей**. Наружние жалюзи, решетки и т.п. крупным планом, окна снаружи при отсутствии защиты.\n'
                 '      - **Входные (наружные) двери**. С внешней стороны крупным планом.\n'
                 '      - **Внутреннее инженерное оборудование**. Сантехника, электрика (внутридомовой электрощит с автоматами в открытом виде), котел, бойлер, батареи, насос, камин,  кондиционер, емкости для топлива и/или воды и т.п. - крупным планом.\n'
                 '      - **Пожарную сигнализацию** - все элементы крупным планом.'
                 '      - **Охранная сигнализация** - все элементы крупным планом;'
                 '      - **Внутреннюю отделку**. Общие планы каждого помещения (с двух противоположных сторон), необходимо отразить все элементы внутренней отделки крупным планом: пол, потолок, стены, двери, встроенная мебель.\n'
                 '      - **Оконный блок: если имеются различные типы окон, то элементы конструкций оконных блоков различных типов, если все окна одинаковы – то достаточно фотографий 1-го оконного блока).\n'
                 '      - **Дефекты и/или повреждения**. Имеющиеся дефекты и/или повреждения отделки и основных конструкций (трещины подтеки, сколы, копоть, влага и т.п.) крупным планом.'
                 '      - **Домашнее имущество в строениях**. Крупным планом каждый предмет, заявляемый на страхование.\n'
                 '      - **Забор:**'
                 '          - С каждой стороны участка забор должен быть снят с внутренней и внешней стороны (т.к. материал забора может быть разный);'
                 '          - Отразить ширину и покрытие дорог, проходящих рядом с забором с каждой стороны участка;'
                 '          - Отразить наличие/отсутствие искусственного рва между забором и дорогой.\n\n'
                 '___Особенность проведения осмотра загородного дома___\n\n'
                 'Только для случаев, когда отсутствуют документы, подтверждающие площадь строения необходимо клиент дополнительно имеет возможность прикрепить нарисованную план-схему загородного дома.\n'
                 'План-схема должна содержать следующую информацию:\n'
                 '      - расположение объектов на участке, включая расстояние между строениями относительно крайних точек объектов, расстояние от объектов до ограждения;\n'
                 '      - основные параметры объектов - внешние замеры длины, ширины, высоты;\n'
                 '      - привязка объектов к улице/дороге/к ограждению участка.\n'
                ,
             'photos': ['static/country_houses/plan-scheme.png']
             }},
    UserRole.MODERATOR.value[0]: {

    },
    UserRole.ADMIN.value[0]:
        {}

}

moderator_helps = {
    'general_info':
        {'title': 'Общие требования',
         'description':
             """
 Осмотр
 объекта
 страхования
 заключается
 в
 проведении
 фото - видеосъемки
 клиентом
 согласно
 требованиям
 страховщика
 в
 зависимости
 от
 объекта
 страхования.
 
 Фото
 и
 видео
 объекта
 направляются
 страховщику
 на
 проверку,
 результатом
 которой
 может
 быть
 согласование
 или
 отправка
 на
 корректировку
 в
 связи
 с
 уточняющими
 вопросами
 или
 исправлением
 фото - видео
 материалов.
 
 По
 результатам
 согласования
 осмотра
 формируется
 акт
 осмотра,
 который
 клиенту
 необходимо
 согласовать(подписать).
 
 Акт
 прикладывается
 к
 комплекту
 документов
 для
 заключения
 договора
 страхования.
 """
         },
}
