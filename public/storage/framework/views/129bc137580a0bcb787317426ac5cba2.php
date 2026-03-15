<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="<?php echo e(route('admin.rapor.index')); ?>" wire:navigate class="text-gray-600 hover:text-gray-900 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Preview Rapor</h1>
                    <p class="text-gray-600 mt-1"><?php echo e($siswa->nama); ?> - <?php echo e($semester->nama ?? ''); ?></p>
                </div>
            </div>
            <div>
                <a href="<?php echo e(route('admin.rapor.print', ['siswaId' => $siswaId, 'semesterId' => $semesterId])); ?>" 
                   target="_blank"
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Rapor Preview -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <!-- Student Identity -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b-2 border-blue-600 pb-2">Identitas Siswa</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nama</p>
                    <p class="font-semibold"><?php echo e($siswa->nama); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">NISN</p>
                    <p class="font-semibold"><?php echo e($siswa->nisn); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Kelas</p>
                    <p class="font-semibold"><?php echo e($kelas->nama ?? '-'); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Semester</p>
                    <p class="font-semibold"><?php echo e($semester->nama ?? '-'); ?></p>
                </div>
            </div>
        </div>

        <!-- Academic Scores -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b-2 border-blue-600 pb-2">Nilai Akademik</h2>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($nilais) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mata Pelajaran</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Pengetahuan</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Keterampilan</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Rata-rata</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $nilais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $nilai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <tr>
                                    <td class="px-4 py-3 text-sm"><?php echo e($index + 1); ?></td>
                                    <td class="px-4 py-3 text-sm"><?php echo e($nilai->mataPelajaran->nama ?? '-'); ?></td>
                                    <td class="px-4 py-3 text-sm text-center"><?php echo e($nilai->nilai_pengetahuan ?? '-'); ?></td>
                                    <td class="px-4 py-3 text-sm text-center"><?php echo e($nilai->nilai_keterampilan ?? '-'); ?></td>
                                    <td class="px-4 py-3 text-sm text-center font-semibold">
                                        <?php echo e($nilai->nilai_pengetahuan && $nilai->nilai_keterampilan ? number_format(($nilai->nilai_pengetahuan + $nilai->nilai_keterampilan) / 2, 2) : '-'); ?>

                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">Belum ada nilai</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Attendance -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b-2 border-blue-600 pb-2">Kehadiran</h2>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kehadiran): ?>
                <div class="grid grid-cols-4 gap-4">
                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Hadir</p>
                        <p class="text-2xl font-bold text-green-600"><?php echo e($kehadiran->hadir ?? 0); ?></p>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Izin</p>
                        <p class="text-2xl font-bold text-yellow-600"><?php echo e($kehadiran->izin ?? 0); ?></p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Sakit</p>
                        <p class="text-2xl font-bold text-blue-600"><?php echo e($kehadiran->sakit ?? 0); ?></p>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Alpha</p>
                        <p class="text-2xl font-bold text-red-600"><?php echo e($kehadiran->alpha ?? 0); ?></p>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">Belum ada data kehadiran</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Teacher Notes -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b-2 border-blue-600 pb-2">Catatan Wali Kelas</h2>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($catatan && $catatan->catatan): ?>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-700"><?php echo e($catatan->catatan); ?></p>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">Belum ada catatan</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH /Users/rahmattanri/Documents/Project Code/raporppsr-deploy/resources/views/livewire/admin/rapor/preview.blade.php ENDPATH**/ ?>