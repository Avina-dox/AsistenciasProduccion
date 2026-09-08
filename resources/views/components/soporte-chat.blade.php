<div x-data="soporteChat()" class="font-century fixed bottom-6 right-6 z-50">

    {{-- Botón flotante --}}
    <button
        @click="open = !open"
        type="button"
        class="relative flex h-14 w-14 items-center justify-center rounded-full text-white shadow-2xl shadow-[#45193F]/40 transition-transform duration-300 hover:scale-105"
        style="background: linear-gradient(135deg, #6A2C75, #45193F);"
        aria-label="Reportar un problema a IT">

        <svg x-show="!open" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3h11A2.5 2.5 0 0 1 20 5.5v8A2.5 2.5 0 0 1 17.5 16H10l-4.5 4v-4H6.5A2.5 2.5 0 0 1 4 13.5v-8Z" />
            <path d="M8 8.5h8M8 11.5h5" />
        </svg>

        <svg x-show="open" style="display:none;" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 6l12 12M18 6 6 18" />
        </svg>

        <span x-show="!open" class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-60" style="background:#D6A644;"></span>
            <span class="relative inline-flex h-3.5 w-3.5 rounded-full" style="background:#D6A644;"></span>
        </span>
    </button>

    {{-- Panel del chat --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.outside="open = false"
        style="display:none;"
        class="absolute bottom-[4.5rem] right-0 flex w-[22rem] max-w-[90vw] flex-col overflow-hidden rounded-2xl border border-[#2B2030]/10 bg-white shadow-2xl shadow-[#45193F]/20">

        {{-- Header --}}
        <div class="flex items-center gap-3 px-5 py-4" style="background: linear-gradient(135deg,#6A2C75,#45193F);">

            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white/15">
                <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3.5 5 6v5.5c0 4.3 2.9 7.4 7 9 4.1-1.6 7-4.7 7-9V6l-7-2.5Z" />
                </svg>
            </div>

            <div class="min-w-0">
                <p class="text-sm font-bold text-white">Soporte IT</p>
                <p class="truncate text-[11px] text-[#E4D9A0]">Tu reporte llega por correo a Diego Aviña</p>
            </div>
        </div>

        {{-- Hilo de mensajes --}}
        <div x-ref="hilo" class="max-h-80 space-y-3 overflow-y-auto px-4 py-4">

            <div class="flex gap-2">
                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full" style="background:#F3EAF5; color:#6A2C75;">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3.5 5 6v5.5c0 4.3 2.9 7.4 7 9 4.1-1.6 7-4.7 7-9V6l-7-2.5Z" />
                    </svg>
                </div>
                <div class="max-w-[80%] rounded-2xl rounded-tl-sm bg-[#F3EDE3] px-3.5 py-2.5 text-sm text-[#2B2030]">
                    ¡Hola! Cuéntame qué problema tienes. Antes de mandarlo a IT te voy a pedir que confirmes que todo está correcto.
                </div>
            </div>

            <template x-for="(m, i) in mensajes" :key="i">
                <div class="flex" :class="m.tipo === 'usuario' ? 'justify-end' : ''">
                    <div
                        class="max-w-[80%] whitespace-pre-wrap rounded-2xl px-3.5 py-2.5 text-sm"
                        :class="m.tipo === 'usuario' ? 'rounded-tr-sm text-white' : 'rounded-tl-sm bg-[#F3EDE3] text-[#2B2030]'"
                        :style="m.tipo === 'usuario' ? 'background: linear-gradient(135deg,#6A2C75,#45193F);' : ''"
                        x-text="m.texto"></div>
                </div>
            </template>

            <div x-show="enviando" style="display:none;" class="flex items-center gap-2 pl-1 text-xs text-[#6E6274]">
                <span class="h-1.5 w-1.5 animate-bounce rounded-full" style="background:#6A2C75;"></span>
                <span class="h-1.5 w-1.5 animate-bounce rounded-full" style="background:#6A2C75; animation-delay:.15s;"></span>
                <span class="h-1.5 w-1.5 animate-bounce rounded-full" style="background:#6A2C75; animation-delay:.3s;"></span>
                Enviando a IT&hellip;
            </div>

        </div>

        {{-- Respuestas rápidas de confirmación --}}
        <div x-show="paso === 'confirmando' && !enviando" style="display:none;" class="flex gap-2 border-t border-[#2B2030]/10 px-4 pt-3">
            <button
                type="button"
                @click="responderRapido('Sí, enviar')"
                class="flex-1 rounded-xl px-3 py-2 text-xs font-semibold text-white transition-transform hover:scale-[1.02]"
                style="background: linear-gradient(135deg,#6A2C75,#45193F);">
                ✓ Sí, enviar
            </button>
            <button
                type="button"
                @click="responderRapido('No, corregir')"
                class="flex-1 rounded-xl border border-[#2B2030]/15 px-3 py-2 text-xs font-semibold text-[#6E6274] transition-colors hover:bg-[#F3EDE3]">
                ✕ No, corregir
            </button>
        </div>

        {{-- Input --}}
        <form @submit.prevent="enviarMensaje()" class="flex items-end gap-2 border-t border-[#2B2030]/10 p-3">

            <textarea
                x-ref="textarea"
                x-model="texto"
                @input="autoGrow($event)"
                rows="1"
                maxlength="2000"
                :placeholder="paso === 'confirmando' ? 'Escribe una corrección o toca una opción…' : 'Describe el problema…'"
                :disabled="enviando"
                class="max-h-24 flex-1 resize-none rounded-xl border border-[#2B2030]/15 px-3 py-2 text-sm text-[#2B2030] focus:outline-none focus:ring-2 focus:ring-[#6A2C75]/30 disabled:opacity-60"></textarea>

            <button
                type="submit"
                :disabled="enviando || !texto.trim()"
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-white transition-transform hover:scale-105 disabled:opacity-40 disabled:hover:scale-100"
                style="background: linear-gradient(135deg,#6A2C75,#45193F);"
                aria-label="Enviar mensaje">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 12 20 4 13 20l-2-6-7-2Z" />
                </svg>
            </button>
        </form>

    </div>
</div>

<script>
    function soporteChat() {
        return {
            open: false,
            texto: '',
            enviando: false,
            mensajes: [],

            // 'preguntando' -> esperando que describan el problema
            // 'confirmando' -> ya hay una descripción, esperando sí/no
            paso: 'preguntando',
            borrador: '',

            autoGrow(evento) {
                evento.target.style.height = 'auto';
                evento.target.style.height = evento.target.scrollHeight + 'px';
            },

            resetAltura() {
                this.$nextTick(() => {
                    if (this.$refs.textarea) {
                        this.$refs.textarea.style.height = 'auto';
                    }
                });
            },

            scrollAbajo() {
                this.$nextTick(() => {
                    if (this.$refs.hilo) {
                        this.$refs.hilo.scrollTop = this.$refs.hilo.scrollHeight;
                    }
                });
            },

            responderBot(texto) {
                this.mensajes.push({
                    tipo: 'bot',
                    texto
                });
                this.scrollAbajo();
            },

            // Envío desde el textarea
            enviarMensaje() {
                const texto = this.texto.trim();

                if (!texto || this.enviando) {
                    return;
                }

                this.mensajes.push({
                    tipo: 'usuario',
                    texto
                });

                this.texto = '';
                this.resetAltura();
                this.scrollAbajo();

                if (this.paso === 'confirmando') {
                    this.manejarConfirmacion(texto);
                } else {
                    this.manejarDescripcion(texto);
                }
            },

            // Envío desde los botones "Sí, enviar" / "No, corregir"
            responderRapido(texto) {
                if (this.enviando) {
                    return;
                }

                this.mensajes.push({
                    tipo: 'usuario',
                    texto
                });
                this.scrollAbajo();
                this.manejarConfirmacion(texto);
            },

            manejarDescripcion(texto) {
                if (texto.length < 12) {
                    this.responderBot('Cuéntame con un poco más de detalle: qué equipo, sistema o pantalla está fallando, y qué mensaje o error ves (si aplica).');
                    return;
                }

                this.borrador = texto;
                this.paso = 'confirmando';
                this.responderBot('Voy a enviar esto a IT:\n\n"' + texto + '"\n\n¿Confirmas el envío?');
            },

            manejarConfirmacion(texto) {
                const primeraPalabra = texto
                    .trim()
                    .toLowerCase()
                    .replace(/[^\p{L}0-9]+/gu, ' ')
                    .trim()
                    .split(/\s+/)[0] || '';

                const afirmativo = ['si', 'sí', 'confirmar', 'enviar', 'ok', 'dale', 'correcto', 'va', 'adelante'].includes(primeraPalabra);
                const negativo = ['no', 'cancelar', 'cancela'].includes(primeraPalabra);

                if (afirmativo) {
                    this.confirmarEnvio();
                    return;
                }

                if (negativo) {
                    this.borrador = '';
                    this.paso = 'preguntando';
                    this.responderBot('Sin problema, lo cancelé. Cuéntame de nuevo el problema cuando gustes.');
                    return;
                }

                // Cualquier otra respuesta se toma como una versión corregida del problema
                if (texto.length < 12) {
                    this.responderBot('Esa descripción es muy corta. Dame un poco más de detalle, o toca "Sí, enviar" / "No, corregir".');
                    return;
                }

                this.borrador = texto;
                this.responderBot('Listo, actualicé la descripción:\n\n"' + texto + '"\n\n¿Confirmas el envío?');
            },

            async confirmarEnvio() {
                this.enviando = true;
                this.scrollAbajo();

                try {

                    const respuesta = await fetch('{{ route('soporte.reportar') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            mensaje: this.borrador,
                            pagina: window.location.href,
                        }),
                    });

                    const datos = await respuesta.json().catch(() => ({}));

                    if (respuesta.ok) {
                        this.responderBot(datos.message || 'Tu reporte fue enviado a IT correctamente.');
                        this.paso = 'preguntando';
                        this.borrador = '';
                    } else {
                        // Se conserva el borrador y el paso "confirmando" para poder reintentar
                        this.responderBot((datos.message || 'No se pudo enviar tu reporte. Intenta de nuevo en unos minutos.') + ' Toca "Sí, enviar" para reintentar.');
                    }

                } catch (error) {

                    this.responderBot('No se pudo enviar tu reporte. Verifica tu conexión e intenta de nuevo tocando "Sí, enviar".');

                } finally {
                    this.enviando = false;
                    this.scrollAbajo();
                }
            },
        };
    }
</script>
