<?php

namespace App\Exports;

use App\Services\AsistenciaService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class AsistenciasExport implements
    FromView,
    ShouldAutoSize,
    WithEvents
{
    protected $desde;

    protected $hasta;

    protected $departamento;

    protected $turno;

    protected $estatus;

    protected AsistenciaService $service;

    public function __construct(
        AsistenciaService $service,
        $desde,
        $hasta,
        $departamento = null,
        $turno = null,
        $estatus = 'ACTIVO'
    ) {

        $this->service = $service;

        $this->desde = $desde;

        $this->hasta = $hasta;

        $this->departamento = $departamento;

        $this->turno = $turno;

        $this->estatus = $estatus;
    }

    public function view(): View
    {
        $reporte = $this->service->obtenerReporte(

            $this->desde,

            $this->hasta,

            $this->departamento,

            $this->turno,

            $this->estatus

        );

        return view(

            'exports.asistencias',

            $reporte

        );
    }
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet;

                $ultimaFila = $sheet->getHighestRow();

                $ultimaColumna = $sheet->getHighestColumn();

                /*
            |--------------------------------------------------------------------------
            | Unir títulos
            |--------------------------------------------------------------------------
            */

                $sheet->mergeCells(
                    'A1:' . $ultimaColumna . '1'
                );

                $sheet->mergeCells(
                    'A2:' . $ultimaColumna . '2'
                );

                /*
            |--------------------------------------------------------------------------
            | Título principal
            |--------------------------------------------------------------------------
            */

                $sheet->getStyle(
                    'A1:' . $ultimaColumna . '2'
                )->applyFromArray([

                    'font' => [

                        'bold' => true,

                        'size' => 16,

                        'color' => [

                            'rgb' => 'FFFFFF'

                        ],

                    ],

                    'alignment' => [

                        'horizontal' => Alignment::HORIZONTAL_CENTER,

                        'vertical' => Alignment::VERTICAL_CENTER,

                    ],

                    'fill' => [

                        'fillType' => Fill::FILL_SOLID,

                        'startColor' => [

                            'rgb' => '6D28D9'

                        ],

                    ],

                ]);
                /*
            |--------------------------------------------------------------------------
            | Encabezados
            |--------------------------------------------------------------------------
            */

                $sheet->getStyle(
                    'A6:' . $ultimaColumna . '6'
                )->applyFromArray([

                    'font' => [

                        'bold' => true,

                        'color' => [

                            'rgb' => 'FFFFFF'

                        ],

                    ],

                    'alignment' => [

                        'horizontal' => Alignment::HORIZONTAL_CENTER,

                        'vertical' => Alignment::VERTICAL_CENTER,

                    ],

                    'fill' => [

                        'fillType' => Fill::FILL_SOLID,

                        'startColor' => [

                            'rgb' => '7C3AED'

                        ],

                    ],

                ]);

                /*
            |--------------------------------------------------------------------------
            | Bordes
            |--------------------------------------------------------------------------
            */

                $sheet->getStyle(

                    'A6:' . $ultimaColumna . $ultimaFila

                )

                    ->getBorders()

                    ->getAllBorders()

                    ->setBorderStyle(

                        Border::BORDER_THIN

                    );

                /*
            |--------------------------------------------------------------------------
            | Centrar contenido
            |--------------------------------------------------------------------------
            */

                $sheet->getStyle(

                    'A6:' . $ultimaColumna . $ultimaFila

                )

                    ->getAlignment()

                    ->setHorizontal(

                        Alignment::HORIZONTAL_CENTER

                    )

                    ->setVertical(

                        Alignment::VERTICAL_CENTER

                    );

                /*
            |--------------------------------------------------------------------------
            | Congelar panel
            |--------------------------------------------------------------------------
            */

                $sheet->freezePane('C7');

                /*
            |--------------------------------------------------------------------------
            | Autofiltro
            |--------------------------------------------------------------------------
            */

                $sheet->setAutoFilter(

                    'A6:' . $ultimaColumna . $ultimaFila

                );

                /*
            |--------------------------------------------------------------------------
            | Altura de filas
            |--------------------------------------------------------------------------
            */

                $sheet->getRowDimension(1)->setRowHeight(28);

                $sheet->getRowDimension(2)->setRowHeight(24);

                $sheet->getRowDimension(6)->setRowHeight(22);

                /*
            |--------------------------------------------------------------------------
            | Negritas para Código y Empleado
            |--------------------------------------------------------------------------
            */

                $sheet->getStyle(

                    'A7:B' . $ultimaFila

                )

                    ->getFont()

                    ->setBold(true);
            },

        ];
    }
}
