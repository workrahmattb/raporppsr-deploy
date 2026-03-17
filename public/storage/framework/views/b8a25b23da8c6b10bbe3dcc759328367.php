<div>
    <!-- Header -->
    <div class="mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pengaturan Rapor</h1>
            <p class="text-gray-600 mt-1">Kelola tanggal dan nama kepala sekolah untuk rapor</p>
        </div>
    </div>

    <!-- Success Message -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form wire:submit="save">
            <!-- Tanggal Rapor -->
            <div class="mb-6">
                <label for="tanggal_rapor" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Rapor <span class="text-red-500">*</span>
                </label>
                <input wire:model="tanggal_rapor" type="text" id="tanggal_rapor" 
                    placeholder="Contoh: ٢ محرم ١٤٤٨, تلوك كوانتن"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['tanggal_rapor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <p class="mt-1 text-sm text-gray-500">Format bebas, bisa menggunakan teks Arabic atau Latin</p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['tanggal_rapor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Kepala Sekolah MTs -->
            <div class="mb-6">
                <label for="kepala_sekolah_mts" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kepala Sekolah MTs (Tingkat 7-9) <span class="text-red-500">*</span>
                </label>
                <input wire:model="kepala_sekolah_mts" type="text" id="kepala_sekolah_mts" 
                    placeholder="Contoh: S.Pd مارديه روسنيله نينغسيه"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['kepala_sekolah_mts'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <p class="mt-1 text-sm text-gray-500">Nama kepala sekolah untuk siswa kelas 7, 8, dan 9</p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['kepala_sekolah_mts'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Kepala Sekolah MA -->
            <div class="mb-6">
                <label for="kepala_sekolah_ma" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kepala Sekolah MA (Tingkat 10-12) <span class="text-red-500">*</span>
                </label>
                <input wire:model="kepala_sekolah_ma" type="text" id="kepala_sekolah_ma" 
                    placeholder="Contoh: Dina Yulesti, M.Pd"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['kepala_sekolah_ma'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <p class="mt-1 text-sm text-gray-500">Nama kepala sekolah untuk siswa kelas 10, 11, dan 12</p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['kepala_sekolah_ma'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Actions -->
            <div class="flex justify-end pt-6 border-t border-gray-200">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
<?php /**PATH /Users/rahmattanri/Documents/Project Code/raporppsr-deploy/resources/views/livewire/admin/rapor-settings/edit.blade.php ENDPATH**/ ?>