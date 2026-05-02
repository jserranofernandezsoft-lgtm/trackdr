<?php

namespace App\Http\Controllers;

use App\Models\ActivoFijo;
use App\Models\ActivoMenor;
use App\Models\Colaborador;
use App\Models\Departamento;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ExportController extends Controller
{
    // =========================================================================
    // PDF — usa una vista Blade simple sin layout
    // =========================================================================

    public function pdfActivosFijos(Request $request)
    {
        $query = ActivoFijo::with(['departamento', 'asignacionActiva.colaborador']);

        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('tipo'))   $query->where('tipo',   $request->tipo);

        $activos  = $query->orderBy('numero_activo')->get();
        $filtros  = $request->only(['estado', 'tipo']);
        $fecha    = now()->format('d/m/Y H:i');
        $titulo   = 'Inventario de Activos Fijos';

        $html = view('exports.pdf_activos_fijos', compact('activos', 'filtros', 'fecha', 'titulo'))->render();

        return response($html)
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    public function pdfActivosMenores(Request $request)
    {
        $query = ActivoMenor::with(['departamento', 'asignacionActiva.colaborador']);

        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('tipo'))   $query->where('tipo',   $request->tipo);

        $activos = $query->orderBy('numero_activo')->get();
        $filtros = $request->only(['estado', 'tipo']);
        $fecha   = now()->format('d/m/Y H:i');
        $titulo  = 'Inventario de Activos Menores';

        $html = view('exports.pdf_activos_menores', compact('activos', 'filtros', 'fecha', 'titulo'))->render();

        return response($html)
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    public function pdfColaborador(Colaborador $colaboradore)
    {
        $colaboradore->load([
            'departamento',
            'asignacionesFijos'   => fn($q) => $q->with('activoFijo')->where('estado', 'activo'),
            'asignacionesMenores' => fn($q) => $q->with('activoMenor')->where('estado', 'activo'),
        ]);

        $fecha  = now()->format('d/m/Y H:i');
        $titulo = 'Activos Asignados — ' . $colaboradore->nombre;

        $html = view('exports.pdf_colaborador', compact('colaboradore', 'fecha', 'titulo'))->render();

        return response($html)
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    public function pdfMantenimientos()
    {
        $mantenimientos = \App\Models\Mantenimiento::with('activoFijo.departamento')
            ->orderBy('estado')
            ->orderByDesc('fecha_entrada')
            ->get();

        $fecha  = now()->format('d/m/Y H:i');
        $titulo = 'Historial de Mantenimientos';

        $html = view('exports.pdf_mantenimientos', compact('mantenimientos', 'fecha', 'titulo'))->render();

        return response($html)
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    // =========================================================================
    // EXCEL — PhpSpreadsheet
    // =========================================================================

    public function excelActivosFijos(Request $request)
    {
        $query = ActivoFijo::with(['departamento', 'asignacionActiva.colaborador']);
        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('tipo'))   $query->where('tipo',   $request->tipo);
        $activos = $query->orderBy('numero_activo')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Activos Fijos');

        // Encabezado empresa
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'INVENTARIO DE ACTIVOS FIJOS — ' . now()->format('d/m/Y'));
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1e293b']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Cabeceras
        $headers = ['# Activo', 'Tipo', 'Marca', 'Modelo', 'Serial', 'Condición', 'Estado', 'Departamento', 'Asignado a', 'Valor'];
        $cols    = range('A', 'J');
        foreach ($headers as $i => $h) {
            $cell = $cols[$i] . '2';
            $sheet->setCellValue($cell, $h);
        }
        $sheet->getStyle('A2:J2')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563eb']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFFFFF']]],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(18);

        // Datos
        $row = 3;
        foreach ($activos as $a) {
            $sheet->setCellValue('A' . $row, $a->numero_activo);
            $sheet->setCellValue('B' . $row, $a->tipo_label);
            $sheet->setCellValue('C' . $row, $a->marca);
            $sheet->setCellValue('D' . $row, $a->modelo);
            $sheet->setCellValue('E' . $row, $a->serial ?? '—');
            $sheet->setCellValue('F' . $row, $a->condicion_label);
            $sheet->setCellValue('G' . $row, $a->estado_label);
            $sheet->setCellValue('H' . $row, $a->departamento?->nombre ?? 'General');
            $sheet->setCellValue('I' . $row, $a->asignacionActiva?->colaborador->nombre ?? '—');
            $sheet->setCellValue('J' . $row, $a->valor_adquisicion ? number_format($a->valor_adquisicion, 2) : '—');

            // Alternar color de filas
            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:J{$row}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'f8fafc']],
                ]);
            }

            // Color por estado
            $colorEstado = match($a->estado) {
                'disponible'    => '166534',
                'asignado'      => '5b21b6',
                'mantenimiento' => '92400e',
                'baja', 'robado_perdido' => '991b1b',
                default => '475569',
            };
            $sheet->getStyle('G' . $row)->getFont()->getColor()->setRGB($colorEstado);
            $sheet->getStyle('G' . $row)->getFont()->setBold(true);

            $row++;
        }

        // Bordes y ancho de columnas
        $sheet->getStyle("A2:J" . ($row - 1))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'e2e8f0']]],
        ]);

        $widths = [14, 16, 14, 16, 16, 12, 16, 22, 24, 12];
        foreach ($cols as $i => $col) {
            $sheet->getColumnDimension($col)->setWidth($widths[$i]);
        }

        // Fila total
        $sheet->setCellValue('A' . $row, 'Total: ' . $activos->count() . ' activos');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setItalic(true);
        $sheet->getStyle('A' . $row)->getFont()->getColor()->setRGB('718096');

        return $this->descargarExcel($spreadsheet, 'activos-fijos-' . now()->format('Ymd'));
    }

    public function excelActivosMenores(Request $request)
    {
        $query = ActivoMenor::with(['departamento', 'asignacionActiva.colaborador']);
        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('tipo'))   $query->where('tipo',   $request->tipo);
        $activos = $query->orderBy('numero_activo')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Activos Menores');

        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'INVENTARIO DE ACTIVOS MENORES — ' . now()->format('d/m/Y'));
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1e293b']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        $headers = ['# Activo', 'Tipo', 'Marca / Modelo', 'Serial', 'Condición', 'Estado', 'Departamento', 'Asignado a'];
        $cols    = range('A', 'H');
        foreach ($headers as $i => $h) {
            $sheet->setCellValue($cols[$i] . '2', $h);
        }
        $sheet->getStyle('A2:H2')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0891b2']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(18);

        $row = 3;
        foreach ($activos as $a) {
            $sheet->setCellValue('A' . $row, $a->numero_activo);
            $sheet->setCellValue('B' . $row, $a->tipo_label);
            $sheet->setCellValue('C' . $row, trim(($a->marca ?? '') . ' ' . ($a->modelo ?? '')));
            $sheet->setCellValue('D' . $row, $a->serial ?? '—');
            $sheet->setCellValue('E' . $row, $a->condicion_label);
            $sheet->setCellValue('F' . $row, $a->estado_label);
            $sheet->setCellValue('G' . $row, $a->departamento?->nombre ?? 'General');
            $sheet->setCellValue('H' . $row, $a->asignacionActiva?->colaborador->nombre ?? '—');

            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'f8fafc']],
                ]);
            }
            $row++;
        }

        $sheet->getStyle("A2:H" . ($row - 1))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'e2e8f0']]],
        ]);

        foreach (['A' => 14, 'B' => 18, 'C' => 22, 'D' => 16, 'E' => 12, 'F' => 14, 'G' => 22, 'H' => 24] as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        return $this->descargarExcel($spreadsheet, 'activos-menores-' . now()->format('Ymd'));
    }

    public function excelColaboradores()
    {
        $colaboradores = Colaborador::with([
            'departamento',
            'asignacionesFijosActivas.activoFijo',
            'asignacionesMenoresActivas.activoMenor',
        ])->orderBy('nombre')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Colaboradores');

        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'INVENTARIO POR COLABORADOR — ' . now()->format('d/m/Y'));
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1e293b']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        $headers = ['Colaborador', 'Correo', 'Cargo', 'Departamento', 'Estado', 'Activos Fijos', 'Activos Menores'];
        foreach (range('A', 'G') as $i => $col) {
            $sheet->setCellValue($col . '2', $headers[$i]);
        }
        $sheet->getStyle('A2:G2')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '7c3aed']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(18);

        $row = 3;
        foreach ($colaboradores as $c) {
            $fijosList   = $c->asignacionesFijosActivas->map(fn($a) => $a->activoFijo->numero_activo . ' ' . $a->activoFijo->nombre_completo)->join(' | ');
            $menoresList = $c->asignacionesMenoresActivas->map(fn($a) => $a->activoMenor->numero_activo . ' ' . $a->activoMenor->nombre_completo)->join(' | ');

            $sheet->setCellValue('A' . $row, $c->nombre);
            $sheet->setCellValue('B' . $row, $c->correo);
            $sheet->setCellValue('C' . $row, $c->cargo ?? '—');
            $sheet->setCellValue('D' . $row, $c->departamento?->nombre ?? '—');
            $sheet->setCellValue('E' . $row, $c->estado === 'activo' ? 'Activo' : 'Inactivo');
            $sheet->setCellValue('F' . $row, $fijosList ?: '—');
            $sheet->setCellValue('G' . $row, $menoresList ?: '—');

            $sheet->getStyle("F{$row}:G{$row}")->getAlignment()->setWrapText(true);

            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'f8fafc']],
                ]);
            }
            $row++;
        }

        $sheet->getStyle("A2:G" . ($row - 1))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'e2e8f0']]],
        ]);

        foreach (['A' => 26, 'B' => 28, 'C' => 20, 'D' => 22, 'E' => 10, 'F' => 40, 'G' => 30] as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        return $this->descargarExcel($spreadsheet, 'colaboradores-' . now()->format('Ymd'));
    }

    // ── Helper: devuelve respuesta de descarga Excel ──────────────────────────
    private function descargarExcel(Spreadsheet $spreadsheet, string $nombre): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        // Estilo global: fuente y alineación
        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);
        $spreadsheet->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $nombre . '.xlsx', [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}
