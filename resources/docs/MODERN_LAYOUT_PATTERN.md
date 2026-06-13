# Padrão de Layout Moderno

Este documento descreve o padrão de layout moderno criado para o sistema, que pode ser reutilizado em todas as páginas.

## Componentes Disponíveis

### 1. `modern-page-header`
Header moderno com gradiente, ícone e área para filtros/ações.

**Uso:**
```blade
<x-modern-page-header
    title="Título da Página"
    subtitle="Subtítulo opcional"
    icon="cog"
    gradient="from-blue-500 via-sky-500 to-cyan-500"
    accentColor="blue"
    :showRefresh="true"
    refreshAction="resetPerfil"
>
    <x-slot name="filters">
        <!-- Filtros aqui -->
    </x-slot>
    
    <x-slot name="actions">
        <!-- Ações aqui -->
    </x-slot>
</x-modern-page-header>
```

**Parâmetros:**
- `title` (string): Título principal
- `subtitle` (string, opcional): Subtítulo
- `icon` (string): Ícone ('cog', 'users', 'organizer', ou padrão)
- `gradient` (string): Classes do gradiente Tailwind
- `accentColor` (string): Cor de destaque ('blue', 'purple', etc.)
- `showRefresh` (bool): Mostrar botão de refresh
- `refreshAction` (string): Método Livewire para refresh

**Slots:**
- `filters`: Área para filtros (opcional)
- `actions`: Área para ações/botões (opcional)

### 2. `modern-tabs`
Sistema de tabs moderno com ícones.

**Uso:**
```blade
<x-modern-tabs
    :tabs="[
        [
            'key' => 'usuarios',
            'label' => 'Usuários',
            'icon' => '<svg>...</svg>'
        ],
        [
            'key' => 'modulos',
            'label' => 'Módulos',
            'icon' => '<svg>...</svg>'
        ]
    ]"
    activeTab="{{ $activeTab }}"
    accentColor="blue"
/>
```

**Parâmetros:**
- `tabs` (array): Array de tabs com 'key', 'label' e opcionalmente 'icon'
- `activeTab` (string): Tab ativa atual
- `accentColor` (string): Cor de destaque

### 3. `modern-empty-state`
Estado vazio moderno com ícone, título e ação opcional.

**Uso:**
```blade
<x-modern-empty-state
    icon="users"
    title="Nenhum usuário encontrado"
    description="Este cliente ainda não possui usuários cadastrados."
    actionLabel="Criar Primeiro Usuário"
    actionMethod="openNewUserModal"
/>
```

**Parâmetros:**
- `icon` (string): Tipo de ícone ('users', 'modules', 'document', ou padrão)
- `title` (string): Título
- `description` (string): Descrição
- `actionLabel` (string, opcional): Label do botão de ação
- `actionMethod` (string, opcional): Método Livewire para ação

### 4. `modern-content-card`
Card de conteúdo com bordas arredondadas.

**Uso:**
```blade
<x-modern-content-card padding="p-6">
    <!-- Conteúdo aqui -->
</x-modern-content-card>
```

**Parâmetros:**
- `padding` (string): Classes de padding (padrão: 'p-6')

## Exemplo Completo

```blade
<div class="mb-10">
    {{-- Header --}}
    <x-modern-page-header
        title="Configurações"
        subtitle="Gerencie usuários e módulos do sistema"
        icon="cog"
        gradient="from-blue-500 via-sky-500 to-cyan-500"
        accentColor="blue"
        :showRefresh="true"
        refreshAction="resetPerfil"
    >
        <x-slot name="filters">
            <div>
                <label class="block text-sm font-medium text-white/90 mb-2">Cliente</label>
                <x-native-select wire:model.live="customerId" class="...">
                    <!-- options -->
                </x-native-select>
            </div>
        </x-slot>
    </x-modern-page-header>

    {{-- Content Card --}}
    <x-modern-content-card>
        {{-- Tabs --}}
        <x-modern-tabs
            :tabs="$tabs"
            activeTab="{{ $activeTab }}"
            accentColor="blue"
        />

        <div class="p-6">
            {{-- Conteúdo das tabs --}}
            @if($activeTab === 'usuarios')
                <!-- Conteúdo -->
            @endif
        </div>
    </x-modern-content-card>
</div>
```

## Cores Disponíveis

### Gradientes
- **Azul**: `from-blue-500 via-sky-500 to-cyan-500`
- **Roxo**: `from-purple-500 via-indigo-500 to-blue-500`
- **Verde**: `from-green-500 via-emerald-500 to-teal-500`
- **Sky**: `from-sky-500 via-sky-400 to-teal-500` (usado em organizadores)

### Accent Colors
- `blue`, `purple`, `green`, `red`, `yellow`, `indigo`, `pink`, etc.

## Padrões de Design

1. **Header**: Sempre com gradiente, padrão de fundo decorativo e elementos blur
2. **Tabs**: Sempre com ícones SVG e transições suaves
3. **Tabelas**: Sempre com hover effects e badges coloridos
4. **Cards**: Sempre com sombra sutil e hover effect
5. **Empty States**: Sempre com ícone SVG grande e mensagem clara
6. **Modais**: Sempre com padding consistente e espaçamento adequado

## Notas

- Todos os componentes são responsivos
- Use sempre transições suaves (`transition-all duration-200`)
- Mantenha consistência nas cores de destaque
- Ícones devem ser SVG inline para melhor controle
- Use backdrop-blur para efeitos modernos

