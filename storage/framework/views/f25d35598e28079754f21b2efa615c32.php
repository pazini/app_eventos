<div class="min-h-screen flex items-center justify-center py-6 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-4xl">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="flex flex-col lg:flex-row">

                <!-- Logo/Brand Section -->
                <div class="lg:w-1/2 bg-gradient-to-br from-teal-700 via-sky-600 to-blue-700 p-4 flex flex-col justify-center items-center text-white relative overflow-hidden">

                    <div class="relative z-10 text-center">
                        <div class="transform hover:scale-105 transition-transform duration-300">
                            <?php echo e($logo); ?>

                        </div>
                    </div>

                </div>

                <!-- Form Section -->
                <div class="lg:w-1/2 p-8 lg:p-12 flex flex-col justify-center">
                    <div class="w-full max-w-sm mx-auto">

                        <div class="space-y-6">
                            <?php echo e($slot); ?>

                        </div>

                        <!-- Footer Info -->
                        <div class="mt-8 pt-6">
                            <div class="flex items-center justify-center space-x-1 gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>Suas informações estão protegidas</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Bottom Gradient -->
        <div class="mt-8 text-center">
            <div class="text-center text-sm text-gray-600 p-2"><?php echo e(appName()); ?> - Copyright © <?php echo e(now()->format('Y')); ?> - Todos os direitos reservados!</div>
        </div>
    </div>
</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/vendor/jetstream/components/authentication-card.blade.php ENDPATH**/ ?>