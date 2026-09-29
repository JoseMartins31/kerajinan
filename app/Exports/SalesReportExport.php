<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SalesReportExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithMapping
{
    protected $data;
    protected $title;

    public function __construct($data, $title = 'Sales Report')
    {
        $this->data = $data;
        $this->title = $title;
    }

    public function collection()
    {
        $collection = collect();

        foreach ($this->data['orders'] as $order) {
            foreach ($order->detailPesanan as $detail) {
                $collection->push((object) [
                    'order' => $order,
                    'detail' => $detail
                ]);
            }
        }

        return $collection;
    }

    public function headings(): array
    {
        return [
            'Tanggal Pesanan',
            'ID Pesanan',
            'Pelanggan',
            'Email',
            'Status Pesanan',
            'Produk',
            'Kategori',
            'Harga Satuan',
            'Jumlah',
            'Sub Total',
            'Total Pesanan',
            'Alamat Pengiriman'
        ];
    }

    public function map($row): array
    {
        return [
            $row->order->tanggal_pesanan->format('d/m/Y H:i'),
            '#' . $row->order->idPesanan,
            $row->order->user->username ?? 'Guest',
            $row->order->user->email ?? 'N/A',
            ucfirst(str_replace('_', ' ', $row->order->status_pesanan)),
            $row->detail->produk->nama_produk,
            $row->detail->produk->kategori->nama_kategori ?? 'N/A',
            'Rp ' . number_format($row->detail->produk->Harga, 0, ',', '.'),
            $row->detail->jumlah,
            'Rp ' . number_format($row->detail->sub_total, 0, ',', '.'),
            'Rp ' . number_format($row->order->total_harga, 0, ',', '.'),
            $row->order->alamat_pengiriman
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row styling
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => '4472C4']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ],

            // All data borders
            'A1:L' . (count($this->collection()) + 1) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ]
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, // Tanggal Pesanan
            'B' => 15, // ID Pesanan
            'C' => 20, // Pelanggan
            'D' => 25, // Email
            'E' => 15, // Status
            'F' => 30, // Produk
            'G' => 15, // Kategori
            'H' => 15, // Harga Satuan
            'I' => 10, // Jumlah
            'J' => 15, // Sub Total
            'K' => 15, // Total Pesanan
            'L' => 40, // Alamat
        ];
    }

    public function title(): string
    {
        return $this->title;
    }
}
