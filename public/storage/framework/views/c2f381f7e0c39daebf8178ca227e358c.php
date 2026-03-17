<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rapor - <?php echo e($siswa->nama); ?></title>
    <style>
        @page {
            margin: 10mm 15mm;
            size: 215mm 330mm; /* F4/Folio */
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #000;
            line-height: 1.3;
        }



        /* Header Styles */
        .header-table {
            width: 100%;
            border-bottom: 4px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        
        .logo-img {
            width: 100px;
            height: auto;
        }
        
        .header-text {
            text-align: center;
            color: #020302ff; /* Green Text */
        }
        
        .header-title {
            font-size: 29pt;
            font-weight: 800;
            margin: 0;
            letter-spacing: 1px;
            font-family: DejaVu Sans
        }
        
        .header-subtitle {
            font-size: 20pt;
            font-weight: 600;
            margin: 5px 0;
            color: #030303ff; /* Yellow/Gold Accent */
            font-family: DejaVu Sans
        }
        
        .header-address {
            font-size: 9pt;
            margin: 0;
            color: #000;
        }

        /* Identity Section */
        .identity-box {
            background-color: #fff;
            border: 1px solid #000;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
        }

        .identity-table {
            width: 100%;
        }

        .identity-table td {
            padding: 4px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            color: #000;
            width: 150px;
            font-family: DejaVu Sans; 
            direction: rtl;
        }

        .separator {
            width: 10px;
            text-align: center;
        }

        .value {
            font-weight: 500;
            color: #000;
            
        }

        /* Section Titles */
        .section-header {
            /* background-color: #006025; */
            color: #080808ff;
            padding: 8px 15px;
            font-size: 16pt;
            font-weight: bold;
            border-radius: 20px 0 20px 0; /* Futuristic shape */
            margin-bottom: 15px;
            display: inline-block;
            white-space: nowrap;
            /* box-shadow: 2px 2px 0px #dcb000; Yellow shadow */
            text-align: right;
            direction: rtl;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            /* Double border effect - outer thick border */
            border: 4px double #000;
            background-color: #fff;
        }

        .data-table th {
            background-color: #fff;
            color: #000;
            padding: 10px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
            border: 1px solid #000;
        }

        .data-table td {
            padding: 8px 10px;
            border: 1px solid #000;
            font-size: 10pt;
            background-color: #fff;
            color: #000;
        }

        .data-table tr:nth-child(even) td {
            background-color: #fff; /* Remove striped rows, keep white */
        }

        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }

        /* Grades Specifics */
        .grade-score {
            font-weight: bold;
            color: #000;
        }

        /* Predikat Colors - all black now */
        .predikat-ممتاز { color: #000; font-weight: bold; }
        .predikat-جيد { color: #000; }
        .predikat-جيد { color: #000; }
        .predikat-مقبول { color: #000; }
        .predikat-ضعيف { color: #000; font-weight: bold; }

        /* Attendance Box */
        .attendance-container {
            width: 100%;
            margin-bottom: 20px;
        }

        .attendance-box {
            display: inline-block;
            width: 30%;
            text-align: center;
            border: 1px solid #ddd;
            padding: 10px;
            margin-right: 2%;
            background: #fff;
        }
        
        .attendance-box.sakit { border-top: 3px solid #000; }
        .attendance-box.izin { border-top: 3px solid #000; }
        .attendance-box.alpha { border-top: 3px solid #000; }

        .att-label { font-size: 9pt; color: #000; margin-bottom: 5px; }
        .att-value { font-size: 14pt; font-weight: bold; color: #000; }

        /* Notes */
        .notes {
            border: 2px dashed #000;
            padding: 15px;
            border-radius: 10px;
            background-color: #fff;
            min-height: 60px;
            direction: rtl;
        }

        /* Footer */
        .footer-table {
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid;
        }
        
        .sign-col {
            width: 33%;
            text-align: center;
            vertical-align: top;
        }
        
        .date-line {
            text-align: right;
            margin-bottom: 10px;
            color: #000;
            font-style: italic;
            padding-right: 20px;
            text-decoration: underline;
            display: inline-block;
            float: right;
        }
        
        .sign-role {
            font-size: 10pt;
            color: #000;
        }

        .sign-spacer {
            height: 300px;
        }
        
        .sign-name {
            font-weight: bold;
            font-size: 13pt;
            text-decoration: underline;
            color: #000;
        }

        .text-right {
            text-align: right;
            font-family: DejaVu Sans;
            direction: rtl;
        }

        

        

    </style>
</head>
<body>


    <!-- Header -->
    <table class="header-table">
        <tr>
            <td width="15%" align="center">
                <img src="<?php echo e(public_path('img/logo-ppsr.png')); ?>" class="logo-img">
            </td>
            <td width="85%" align="center">
                <div class="header-text">
                    <h1 class="header-title">لطلبة معهد شفاعة الرسول الاسلامي</h1>
                    <h2 class="header-subtitle">كشف الدرجات</h2>
                    <!-- <p class="header-address">Teluk Kuantan, Kabupaten Kuantan Singingi, Riau</p>
                    <p class="header-address">Website: www.syafaaturrasul.com | Email: info@syafaaturrasul.com</p> -->
                </div>
            </td>
        </tr>
    </table>

    <!-- Identity -->
    <div class="identity-box">
        <table class="identity-table">
            <tr>
                <td class="label">اسم الطالب / الطالبة</td>
                <td class="separator">:</td>
                <td style="font-size:12px; font-family: DejaVu Sans;" width="40%"><?php echo e(strtoupper($siswa->nama_arabic)); ?></td>
                <td class="label">العام الدراسي</td>
                <td class="separator">:</td>
                <td style="font-size:12px; font-family: DejaVu Sans;">
                    <?php
                        // Function to convert Latin numerals to Eastern Arabic numerals
                        function toArabicNumerals($number) {
                            $latinNumerals = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                            $arabicNumerals = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
                            return str_replace($latinNumerals, $arabicNumerals, $number);
                        }
                        $tahunAjaran = $semester->tahunAjaran->tahun ?? '-';
                    ?>
                    <?php echo e(toArabicNumerals($tahunAjaran)); ?>

                </td>
            </tr>
            <tr>
                <td class="label">الفصل</td>
                <td class="separator">:</td>
                <td class="value"><?php echo e($kelas->nama ?? '-'); ?></td>
                <!-- <td class="label">NISN / NIS</td>
                <td class="separator">:</td>
                <td class="value"><?php echo e($siswa->nisn); ?> / <?php echo e($siswa->nis ?? '-'); ?></td> -->
                <td class="label">الفصل الدراسي</td>
                <td class="separator">:</td>
                <td class="value">
                    <?php
                        // Convert semester name to Arabic
                        // Ganjil (Odd) = الأول (al-awwal / first)
                        // Genap (Even) = الثاني (ats-tsani / second)
                        $semesterNama = $semester->nama ?? '';
                        $semesterArabic = '-';
                        
                        if (stripos($semesterNama, 'ganjil') !== false || stripos($semesterNama, '1') !== false) {
                            $semesterArabic = 'الأول';
                        } elseif (stripos($semesterNama, 'genap') !== false || stripos($semesterNama, '2') !== false) {
                            $semesterArabic = 'الثاني';
                        }
                    ?>
                    <?php echo e($semesterArabic); ?>

                </td>
            </tr>
            <tr>
                
                <!-- <td class="label">Tingkat</td>
                <td class="separator">:</td>
                <td class="value"><?php echo e($kelas->tingkat ?? '-'); ?></td> -->
            </tr>
        </table>
    </div>

    <!-- Academic Scores -->
    <div class="section-header">تحقيق الكفاءات والتحصيل الأكاديمي</div>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($nilais) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="25%"
                        style="font-size:20px; font-family: DejaVu Sans; direction: rtl;">
                        التقدير
                    </th>
                    <th width="20%"
                        style="font-size:20px; font-family: DejaVu Sans; direction: rtl;">
                        رقما
                    </th>
                    <th width="50%"
                        style="font-size:20px; font-family: DejaVu Sans; direction: rtl;">
                        المواد الدراسية
                    </th>
                    <th width="5%"
                        style="font-size:20px; font-family: DejaVu Sans; direction: rtl;">
                        النمرة
                    </th>
                </tr>

            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $nilais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $nilai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <?php
                        // Get nilai_pengetahuan, treat null and empty as 0
                        $nilaiPengetahuan = $nilai->nilai_pengetahuan ?? 0;
                        
                        // Check if nilai is empty, null, or 0
                        $isEmptyNilai = empty($nilaiPengetahuan) || $nilaiPengetahuan == 0;
                        
                        // Only calculate predikat if nilai is not empty
                        $predikat = $isEmptyNilai ? '-' : match(true) {
                            $nilaiPengetahuan >= 90 => 'ممتاز',
                            $nilaiPengetahuan >= 80 => 'جيد جدًا',
                            $nilaiPengetahuan >= 70 => 'جيد',
                            $nilaiPengetahuan >= 60 => 'مقبول',
                            default => 'ضعيف',
                        };
                        
                        $predClass = $isEmptyNilai ? '' : 'predikat-' . $predikat;
                    ?>
                    <tr>
                        <td class="center <?php echo e($predClass); ?>">
                            <?php echo e($predikat); ?>

                        </td>
                        <td class="center grade-score"><?php echo e($isEmptyNilai ? '-' : toArabicNumerals($nilaiPengetahuan)); ?></td>
                        <td class="text-right"><?php echo e($nilai->mataPelajaran->namapelajaran_arabic ?? '-'); ?></td>
                        <td class="center"><?php echo e(toArabicNumerals($index + 1)); ?></td>
                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                
                <?php
                    // Calculate total and average
                    $totalNilai = 0;
                    $countNilai = 0;
                    
                    foreach($nilais as $nilai) {
                        $nilaiPengetahuan = $nilai->nilai_pengetahuan ?? 0;
                        if ($nilaiPengetahuan > 0) {
                            $totalNilai += $nilaiPengetahuan;
                            $countNilai++;
                        }
                    }
                    
                    $average = $countNilai > 0 ? round($totalNilai / $countNilai, 2) : 0;
                ?>
                
                <!-- Total Row -->
                <tr style="background-color: #fff; font-weight: bold; border: 1px solid #000;">
                    <td class="center grade-score" style="font-size:14px; padding: 8px; color: #000;">
                        <?php echo e($totalNilai > 0 ? toArabicNumerals($totalNilai) : '-'); ?>

                    </td>
                    <td colspan="2" class="center" style="font-size:16px; font-family: DejaVu Sans; direction: rtl; padding: 8px; color: #000;">
                        المجموع
                    </td>
                    <td></td>
                </tr>
                
                <!-- Average Row -->
                <tr style="background-color: #fff; font-weight: bold; border: 1px solid #000;">
                    <td class="center grade-score" style="font-size:14px; padding: 8px; color: #000;">
                        <?php echo e($average > 0 ? toArabicNumerals(number_format($average, 2)) : '-'); ?>

                    </td>
                    <td colspan="2" class="center" style="font-size:16px; font-family: DejaVu Sans; direction: rtl; padding: 8px; color: #000;">
                        المعدل
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <p class="center" style="font-style: italic; color: #777; margin: 20px;">Belum ada data nilai akademik</p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Attendance
    <div class="section-header">B. KETIDAKHADIRAN</div>
    
    
    <table width="100%" style="margin-bottom: 20px;">
        <tr>
            <td width="33%" style="padding-right: 10px;">
                <div style="border: 1px solid #ddd; border-top: 3px solid #3498db; padding: 10px; text-align: center; background: #fff;">
                    <div class="att-label">SAKIT</div>
                    <div class="att-value"><?php echo e($kehadiran->sakit ?? 0); ?></div>
                    <div style="font-size: 8pt; color: #999;">Hari</div>
                </div>
            </td>
            <td width="33%" style="padding: 0 5px;">
                <div style="border: 1px solid #ddd; border-top: 3px solid #f1c40f; padding: 10px; text-align: center; background: #fff;">
                    <div class="att-label">IZIN</div>
                    <div class="att-value"><?php echo e($kehadiran->izin ?? 0); ?></div>
                    <div style="font-size: 8pt; color: #999;">Hari</div>
                </div>
            </td>
            <td width="33%" style="padding-left: 10px;">
                <div style="border: 1px solid #ddd; border-top: 3px solid #e74c3c; padding: 10px; text-align: center; background: #fff;">
                    <div class="att-label">ALPHA</div>
                    <div class="att-value"><?php echo e($kehadiran->alpha ?? 0); ?></div>
                    <div style="font-size: 8pt; color: #999;">Hari</div>
                </div>
            </td>
        </tr>
    </table> -->

    <!-- Notes -->
    <div class="section-header">الملاحظات</div>
    <div class="notes">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($catatan && $catatan->catatan): ?>
            <?php echo e($catatan->catatan); ?>

        <?php else: ?>
            <span style="color: #999; font-style: italic; font-family: DejaVu Sans; direction: rtl;">واصل تحسين إنجازاتك الدراسية، واحرص على الجد في الدراسة والعبادة</span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Signatures Tulis Tanggal Cetak Rapornya disini -->
    <div class="date-line">
        <span style="font-family: DejaVu Sans; font-size: 18px; direction: rtl;"><?php echo e($settings->tanggal_rapor ?? '٢ محرم ١٤٤٨, تلوك كوانتن'); ?></span>
    </div>
    
    <table class="footer-table">
        <tr>
            <td class="sign-col">
                <div class="sign-role">ولي الامر</div>  
                <div class="sign-spacer">&nbsp;</div>
                <div class="sign-spacer">&nbsp;</div>
                <div class="sign-spacer">&nbsp;</div>
                <div class="sign-spacer">&nbsp;</div>
                <div class="sign-name">(....................................)</div>
            </td>
            <td class="sign-col">
                <div class="sign-role">ولية الفصل</div>
                <div class="sign-spacer">&nbsp;</div>
                <div class="sign-spacer">&nbsp;</div>
                <div class="sign-spacer">&nbsp;</div>
                <div class="sign-spacer">&nbsp;</div>
                <div class="sign-name">
                    <?php echo e($kelas->waliKelas->nama_arabic ?? $kelas->waliKelas->nama ?? '(....................................)'); ?>

                </div>
            </td>
            <td class="sign-col">
                <div class="sign-role">رئيسة المدرسة</div>
                <div class="sign-spacer">&nbsp;</div>
                <div class="sign-spacer">&nbsp;</div>
                <div class="sign-spacer">&nbsp;</div>
                <div class="sign-spacer">&nbsp;</div>
                <?php
                    // Determine principal name based on grade level (tingkat)
                    // Tingkat 7, 8, 9 (MTs) = from settings->kepala_sekolah_mts
                    // Tingkat 10, 11, 12 (MA) = from settings->kepala_sekolah_ma
                    $tingkat = $kelas->tingkat ?? null;
                    $kepalaMadrasah = $settings->kepala_sekolah_ma ?? 'Dina Yulesti, M.Pd'; // Default for MA (10, 11, 12)
                    
                    if (in_array($tingkat, [7, 8, 9, '7', '8', '9', 'VII', 'VIII', 'IX'])) {
                        $kepalaMadrasah = $settings->kepala_sekolah_mts ?? 'S.Pd مارديه روسنيله نينغسيه';
                    }
                ?>
                <div class="sign-name"><?php echo e($kepalaMadrasah); ?></div>
            </td>
        </tr>
    </table>

    <!-- Student Name in Latin (Bottom Left) -->
    <div style="margin-top: 20px; font-size: 9pt; color: #666;">
        <strong>Nama Siswa:</strong> <?php echo e($siswa->nama); ?>

    </div>

</body>
</html>
<?php /**PATH /Users/rahmattanri/Documents/Project Code/raporppsr-deploy/resources/views/livewire/admin/rapor/print.blade.php ENDPATH**/ ?>