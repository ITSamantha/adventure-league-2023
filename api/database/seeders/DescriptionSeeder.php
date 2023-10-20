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
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'общий вид участка: несколько ракурсов участка для определения расстояний между объектами страхования, ограждением (забором), соседними сооружениями/зданиями, подъездными дорогами, объектами повышенного риска (стройка, водоемы и т.п.);',
            'min_count' => 1
        ],
        2 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'наружные инженерные коммуникации и сооружения: электроснабжения, водоснабжения, водоотведения, теплоснабжения, такие как: септик, эл.станция, трансформатор, распределительный щит, скважина, колодец, насос и т.п.;',
            'min_count' => 0
        ],
        3 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'фасады строений: каждое строение с 4-х сторон, элементы внешней отделки фасадов, кровлю, фундамент;',
            'min_count' => 0
        ],
        4 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'механическую защиту окон и дверей: наружние жалюзи, решетки и т.п. крупным планом, окна снаружи при отсутствии защиты;',
            'min_count' => 1
        ],
        5 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'входные (наружные) двери: с внешней стороны крупным планом;',
            'min_count' => 1
        ],
        6 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'внутреннее инженерное оборудование: сантехника, электрика (внутридомовой электрощит с автоматами в открытом виде), котел, бойлер, батареи, насос, камин,  кондиционер, емкости для топлива и/или воды и т.п. - крупным планом;',
            'min_count' => 1
        ],
        7 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'пожарную сигнализацию - все элементы крупным планом;',
            'min_count' => 1
        ],
        8 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'охранная сигнализация - все элементы крупным планом;',
            'min_count' => 1
        ],
        9 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'внутреннюю отделку: общие планы каждого помещения (с двух противоположных сторон), необходимо отразить все элементы внутренней отделки крупным планом: пол, потолок, стены, двери, встроенная мебель;',
            'min_count' => 2
        ],
        10 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'оконный блок: если имеются различные типы окон, то элементы конструкций оконных блоков различных типов, если все окна одинаковы – то достаточно фотографий 1-го оконного блока);',
            'min_count' => 1
        ],
        11 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'дефекты и/или повреждения: имеющиеся дефекты и/или повреждения отделки и основных конструкций (трещины подтеки, сколы, копоть, влага и т.п.) крупным планом;',
            'min_count' => 0
        ],
        12 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'домашнее имущество в строениях: крупным планом каждый предмет, заявляемый на страхование;',
            'min_count' => 0
        ],
        13 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'забор:
                с каждой стороны участка забор должен быть снят с внутренней и внешней стороны (т.к. материал забора может быть разный),
                отразить ширину и покрытие дорог, проходящих рядом с забором с каждой стороны участка,
                отразить наличие/отсутствие искусственного рва между забором и дорогой.',
            'min_count' => 1
        ],
        14 => [
            'filetype' => FileType::DOCUMENT,
            'type' => InsuranceObjectType::COUNTRY_HOUSE,
            'text' => 'План-схема должна содержать следующую информацию:
    расположение объектов на участке, включая расстояние между строениями относительно крайних точек объектов, расстояние от объектов до ограждения;
    основные параметры объектов - внешние замеры длины, ширины, высоты;
    привязка объектов к улице/дороге/к ограждению участка.',
            'min_count' => 0,
            'is_editable' => true,
        ],

        15 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::CAR,
            'text' => 'фото VIN-номера на металле – минимум 1 фото',
            'min_count' => 1
        ],
        16 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::CAR,
            'text' => 'фото транспортного средства снаружи – минимум 8 фото: с 4-х сторон + с 4-х углов (допускается больше при необходимости)',
            'min_count' => 8
        ],
        17 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::CAR,
            'text' => 'фото лобового стекла – минимум 1 фото',
            'min_count' => 1
        ],
        18 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::CAR,
            'text' => 'фото маркировки лобового стекла – 1 фото',
            'min_count' => 1
        ],
        19 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::CAR,
            'text' => 'фото колеса в сборе – минимум 1 фото (должны читаться размер и производитель шины);',
            'min_count' => 1
        ],
        20 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::CAR,
            'text' => 'фото показаний одометра (пробег) – 1 фото',
            'min_count' => 1
        ],
        21 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::CAR,
            'text' => 'фото салона – минимум 2 фото: передняя часть салона с приборной панелью + задняя часть салона',
            'min_count' => 2
        ],
        22 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::CAR,
            'text' => 'фото всех повреждений (при наличии) – неограниченное количество фото',
            'min_count' => 0
        ],
        23 => [
            'filetype' => FileType::PHOTO,
            'type' => InsuranceObjectType::CAR,
            'text' => 'фото штатных ключей + ключей/брелоков/меток от дополнительных противоугонных устройств.',
            'min_count' => 1
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
