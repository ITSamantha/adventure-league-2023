<?php

namespace Database\Seeders;

use App\Models\FileDescription;
use App\Models\FileType;
use App\Models\InsuranceObjectFileType;
use App\Models\InsuranceObjectType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DescriptionSeeder extends Seeder
{

    protected array $data = [
        1 => [
            'filetype' => FileType::TEXT,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'Адрес дома',
            'min_count' => 0
        ],
        2 => [
            'filetype' => FileType::TEXT,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'Год постройки',
            'min_count' => 0
        ],
        3 => [
            'filetype' => FileType::TEXT,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'Материал стен',
            'min_count' => 0
        ],
        4 => [
            'filetype' => FileType::TEXT,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'Площадь',
            'min_count' => 0
        ],

        35 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'общий вид участка: несколько ракурсов участка для определения расстояний между объектами страхования, ограждением (забором), соседними сооружениями/зданиями, подъездными дорогами, объектами повышенного риска (стройка, водоемы и т.п.);',
            'min_count' => 1
        ],
        36 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'наружные инженерные коммуникации и сооружения: электроснабжения, водоснабжения, водоотведения, теплоснабжения, такие как: септик, эл.станция, трансформатор, распределительный щит, скважина, колодец, насос и т.п.;',
            'min_count' => 0
        ],
        37 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'фасады строений: каждое строение с 4-х сторон, элементы внешней отделки фасадов, кровлю, фундамент;',
            'min_count' => 0
        ],
        38 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'механическую защиту окон и дверей: наружние жалюзи, решетки и т.п. крупным планом, окна снаружи при отсутствии защиты;',
            'min_count' => 1
        ],
        39 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'входные (наружные) двери: с внешней стороны крупным планом;',
            'min_count' => 1
        ],
        40 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'внутреннее инженерное оборудование: сантехника, электрика (внутридомовой электрощит с автоматами в открытом виде), котел, бойлер, батареи, насос, камин,  кондиционер, емкости для топлива и/или воды и т.п. - крупным планом;',
            'min_count' => 1
        ],
        41 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'пожарную сигнализацию - все элементы крупным планом;',
            'min_count' => 1
        ],
        42 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'охранная сигнализация - все элементы крупным планом;',
            'min_count' => 1
        ],
        43 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'внутреннюю отделку: общие планы каждого помещения (с двух противоположных сторон), необходимо отразить все элементы внутренней отделки крупным планом: пол, потолок, стены, двери, встроенная мебель;',
            'min_count' => 2
        ],
        44 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'оконный блок: если имеются различные типы окон, то элементы конструкций оконных блоков различных типов, если все окна одинаковы – то достаточно фотографий 1-го оконного блока);',
            'min_count' => 1
        ],
        45 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'дефекты и/или повреждения: имеющиеся дефекты и/или повреждения отделки и основных конструкций (трещины подтеки, сколы, копоть, влага и т.п.) крупным планом;',
            'min_count' => 0
        ],
        46 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'домашнее имущество в строениях: крупным планом каждый предмет, заявляемый на страхование;',
            'min_count' => 0
        ],
        47 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'забор:
                с каждой стороны участка забор должен быть снят с внутренней и внешней стороны (т.к. материал забора может быть разный),
                отразить ширину и покрытие дорог, проходящих рядом с забором с каждой стороны участка,
                отразить наличие/отсутствие искусственного рва между забором и дорогой.',
            'min_count' => 1
        ],
        48 => [
            'filetype' => FileType::DOCUMENT,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'План-схема должна содержать следующую информацию:
    расположение объектов на участке, включая расстояние между строениями относительно крайних точек объектов, расстояние от объектов до ограждения;
    основные параметры объектов - внешние замеры длины, ширины, высоты;
    привязка объектов к улице/дороге/к ограждению участка.',
            'min_count' => 0,
            'is_editable' => true,
        ],

        49 => [
            'filetype' => FileType::TEXT,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'Комментарий',
            'min_count' => 0
        ],


        50 => [
            'filetype' => FileType::TEXT,
            'type' => InsuranceObjectType::CAR,
            'text' => 'Марка, модель',
            'min_count' => 0
        ],
        51 => [
            'filetype' => FileType::TEXT,
            'type' => InsuranceObjectType::CAR,
            'text' => 'Год выпуска',
            'min_count' => 0
        ],
        52 => [
            'filetype' => FileType::TEXT,
            'type' => InsuranceObjectType::CAR,
            'text' => 'Собственник (ФИО)',
            'min_count' => 0
        ],
        53 => [
            'filetype' => FileType::TEXT,
            'type' => InsuranceObjectType::CAR,
            'text' => 'VIN или кузов/шасси №',
            'min_count' => 0
        ],
        54 => [
            'filetype' => FileType::TEXT,
            'type' => InsuranceObjectType::CAR,
            'text' => 'Государственный регистрационный знак',
            'min_count' => 0
        ],
        55 => [
            'filetype' => FileType::TEXT,
            'type' => InsuranceObjectType::CAR,
            'text' => 'Пробег',
            'min_count' => 0
        ],
        56 => [
            'filetype' => FileType::TEXT,
            'type' => InsuranceObjectType::CAR,
            'text' => 'Диски (литые / штампованный / кованые)',
            'min_count' => 0
        ],
        57 => [
            'filetype' => FileType::TEXT,
            'type' => InsuranceObjectType::CAR,
            'text' => 'Покрышки (зимние, летние, всесезонные)',
            'min_count' => 0
        ],


        91 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::CAR,
            'text' => 'фото VIN-номера на металле – минимум 1 фото',
            'min_count' => 1
        ],
        92 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::CAR,
            'text' => 'фото транспортного средства снаружи – минимум 8 фото: с 4-х сторон + с 4-х углов (допускается больше при необходимости)',
            'min_count' => 8
        ],
        93 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::CAR,
            'text' => 'фото лобового стекла – минимум 1 фото',
            'min_count' => 1
        ],
        94 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::CAR,
            'text' => 'фото маркировки лобового стекла – 1 фото',
            'min_count' => 1
        ],
        95 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::CAR,
            'text' => 'фото колеса в сборе – минимум 1 фото (должны читаться размер и производитель шины);',
            'min_count' => 1
        ],
        96 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::CAR,
            'text' => 'фото показаний одометра (пробег) – 1 фото',
            'min_count' => 1
        ],
        97 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::CAR,
            'text' => 'фото салона – минимум 2 фото: передняя часть салона с приборной панелью + задняя часть салона',
            'min_count' => 2
        ],
        98 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::CAR,
            'text' => 'фото всех повреждений (при наличии) – неограниченное количество фото',
            'min_count' => 0
        ],
        99 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::CAR,
            'text' => 'фото штатных ключей + ключей/брелоков/меток от дополнительных противоугонных устройств.',
            'min_count' => 1
        ],
        100 => [
            'filetype' => FileType::TEXT,
            'type' => InsuranceObjectType::CAR,
            'text' => 'Комментарий',
            'min_count' => 0
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            foreach ($this->data as $id => $row) {
                FileDescription::query()->updateOrCreate(['id' => $id], [
                    'content' => $row['text'],
                ]);
                InsuranceObjectFileType::query()->updateOrCreate([
                    'file_type_id' => $row['filetype'],
                    'file_description_id' => $id,
                    'insurance_object_type_id' => $row['type'],
                ], [
                    'min_photo_count' => $row['min_count'],
                    'is_editable' => $row['is_editable'] ?? false,
                ]);
            }
        });
    }
}
