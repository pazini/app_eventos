-- =====================================================
-- Inserts iniciais para a tabelas fixas de referência
-- =====================================================

-- Estados (UF)
INSERT INTO ref_app_states
(created_at, updated_at, ref_slug, ref_value, ref_label, ref_description, ref_placeholder, ref_options, to_view, ref_icon, ref_color, ref_color_bg)
VALUES
(now(), now(), 'ac', 'AC - Rio Branco', 'AC - Rio Branco', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'al', 'AL - Maceió', 'AL - Maceió', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'ap', 'AP - Macapá', 'AP - Macapá', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'am', 'AM - Manaus', 'AM - Manaus', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'ba', 'BA - Salvador', 'BA - Salvador', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'ce', 'CE - Fortaleza', 'CE - Fortaleza', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'df', 'DF - Brasília', 'DF - Brasília', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'es', 'ES - Vitória', 'ES - Vitória', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'go', 'GO - Goiânia', 'GO - Goiânia', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'ma', 'MA - São Luís', 'MA - São Luís', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'mt', 'MT - Cuiabá', 'MT - Cuiabá', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'ms', 'MS - Campo Grande', 'MS - Campo Grande', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'mg', 'MG - Belo Horizonte', 'MG - Belo Horizonte', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'pa', 'PA - Belém', 'PA - Belém', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'pb', 'PB - João Pessoa', 'PB - João Pessoa', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'pr', 'PR - Curitiba', 'PR - Curitiba', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'pe', 'PE - Recife', 'PE - Recife', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'pi', 'PI - Teresina', 'PI - Teresina', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'rj', 'RJ - Rio de Janeiro', 'RJ - Rio de Janeiro', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'rn', 'RN - Natal', 'RN - Natal', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'rs', 'RS - Porto Alegre', 'RS - Porto Alegre', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'ro', 'RO - Porto Velho', 'RO - Porto Velho', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'rr', 'RR - Boa Vista', 'RR - Boa Vista', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'sc', 'SC - Florianópolis', 'SC - Florianópolis', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'sp', 'SP - São Paulo', 'SP - São Paulo', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'se', 'SE - Aracaju', 'SE - Aracaju', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'to', 'TO - Palmas', 'TO - Palmas', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700')
ON CONFLICT (ref_slug) DO UPDATE
SET ref_value = EXCLUDED.ref_value,
    updated_at = EXCLUDED.updated_at,
    to_view = true;

-- Tipos de evento
INSERT INTO ref_app_event_type
(created_at, updated_at, ref_slug, ref_value, ref_label, ref_description, ref_placeholder, ref_options, to_view, ref_icon, ref_color, ref_color_bg)
VALUES
(now(), now(), 'congresso', 'Congresso', 'Congresso', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'conference', 'Conference', 'Conference', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'evento', 'Evento', 'Evento', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'acampamento', 'Acampamento', 'Acampamento', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700'),
(now(), now(), 'venda-de-produto', 'Venda de Produtos', 'Venda de Produtos', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700')
ON CONFLICT (ref_slug) DO UPDATE
SET ref_value = EXCLUDED.ref_value,
    updated_at = EXCLUDED.updated_at,
    to_view = true;

-- Categorias de evento
INSERT INTO ref_app_event_category
(created_at, updated_at, ref_slug, ref_value, ref_label, ref_description, ref_placeholder, ref_options, to_view, ref_icon, ref_color, ref_color_bg)
VALUES
(now(), now(), 'pago', 'Pago', 'Pago', NULL, NULL, NULL, true, 'check-circle', 'white', 'gray-700')
ON CONFLICT (ref_slug) DO UPDATE
SET ref_value = EXCLUDED.ref_value,
    updated_at = EXCLUDED.updated_at,
    to_view = true;


-- =====================================================
-- Inserts iniciais para a tabela tb_app
-- =====================================================

-- Insert do app padrão EmpresaTeste para desenvolvimento
-- Usa ON CONFLICT DO NOTHING para evitar erro se já existir
INSERT INTO tb_app (
    id,
    app_name,
    app_description,
    app_license,
    app_limit_date,
    app_active,
    owner_name,
    owner_email,
    owner_phone_country,
    owner_phone_ddd,
    owner_phone_num,
    url_base,
    url_image_logo,
    domain_primary,
    domain_aliases,
    color_primary,
    color_secondary,
    color_accent,
    url_image_logo_dark,
    url_image_favicon,
    email_from_name,
    email_from_address,
    email_reply_to,
    meta_title,
    meta_description,
    meta_keywords,
    meta_image,
    settings,
    branding_updated_at,
    created_at,
    updated_at
) VALUES (
    'abcd0000-0000-0000-0000-000000000003',
    'EmpresaTeste',
    'Plataforma de Gestão de Eventos e Campanhas',
    'development',
    NULL,
    true,
    'Administrador',
    'admin@empresateste.com',
    '55',
    '11',
    '999999999',
    'http://127.0.0.1:8000',
    NULL,
    '127.0.0.1',
    '["localhost", "empresateste.com", "painel.empresateste.com", "eventos.empresateste.com", "campanhas.empresateste.com"]',
    '#3B82F6',
    '#10B981',
    '#F59E0B',
    NULL,
    NULL,
    'EmpresaTeste',
    'noreply@empresateste.com',
    'suporte@empresateste.com',
    'EmpresaTeste - Gestão de Eventos e Campanhas',
    'Plataforma completa para gestão de eventos e campanhas promocionais',
    'eventos, campanhas, ingressos, gestão',
    NULL,
    '{"features":{"events":true,"campaigns":true,"payments":true,"notifications":true},"modules":{"eventos":true,"campanhas":true}}',
    NOW(),
    NOW(),
    NOW()
)
ON CONFLICT (id) DO NOTHING;

-- =====================================================
-- Inserts iniciais para usuário super administrador
-- =====================================================

-- Insert do usuário super administrador
-- Email: admin@empresateste.com
-- Senha: admin123 (LEMBRE-SE DE ALTERAR EM PRODUÇÃO!)
-- Usa ON CONFLICT DO NOTHING para evitar erro se já existir
INSERT INTO users (
    id,
    name,
    email,
    email_verified_at,
    password,
    birth_date,
    doc_type,
    doc_num,
    contact_country,
    contact_ddd,
    contact_num,
    profile_photo_path,
    remember_token,
    created_at,
    updated_at
) VALUES (
    'abcd0000-0000-0000-0000-000000000001',
    'Super Administrador',
    'admin@empresateste.com',
    NOW(),
    '$2y$10$4HpN7DhGQqTZCs8Et00sY.okJVLm1dR.9uGGpfk2j0gxJ5HVfha2y', -- Senha: admin123
    NULL,
    'CPF',
    '00000000000',
    55,
    11,
    999999999,
    NULL,
    NULL,
    NOW(),
    NOW()
)
ON CONFLICT (id) DO NOTHING;

-- Relaciona o usuário super admin com o app EmpresaTeste
-- Role: owner (proprietário/super admin)
-- Usa ON CONFLICT DO NOTHING para evitar erro se já existir
INSERT INTO users_app (
    id,
    app_id,
    user_id,
    user_active,
    user_role,
    created_at,
    updated_at
) VALUES (
    'abcd0000-0000-0000-0000-000000000002',
    'abcd0000-0000-0000-0000-000000000003', -- ID do app EmpresaTeste
    'abcd0000-0000-0000-0000-000000000001', -- ID do super admin
    true,
    'admin',
    NOW(),
    NOW()
)
ON CONFLICT (id) DO NOTHING;

-- =====================================================
-- Customer Padrão do APP EmpresaTeste
-- =====================================================

-- Insert do customer padrão (conta administrativa do APP)
INSERT INTO tb_customers (
    id,
    app_id,
    customer_slug,
    prefix_url,
    name_corporate,
    name_fantasy,
    name_short,
    doc_type,
    doc_num,
    comercial_contact_name,
    comercial_contact_email,
    comercial_contact_country,
    comercial_contact_ddd,
    comercial_contact_num,
    generate_invoice,
    created_at,
    updated_at
) VALUES (
    'abcd0000-0000-0000-0000-000000000003',
    'abcd0000-0000-0000-0000-000000000003', -- ID do app EmpresaTeste
    'empresa-teste',
    'empresa-teste',
    'EmpresaTeste',
    'EmpresaTeste',
    'EmpresaTeste',
    'CNPJ',
    '00000000000000', -- Placeholder
    'Administrador',
    'admin@empresateste.com',
    55,
    11,
    999999999,
    0,
    NOW(),
    NOW()
)
ON CONFLICT (id) DO NOTHING;

-- Associar o super admin ao customer padrão com todas as permissões
INSERT INTO users_customer (
    id,
    customer_id,
    user_id,
    user_active,
    user_role,
    can_events,
    can_campaigns,
    created_at,
    updated_at
) VALUES (
    'abcd0000-0000-0000-0000-000000000004',
    'abcd0000-0000-0000-0000-000000000003', -- ID do customer EmpresaTeste
    'abcd0000-0000-0000-0000-000000000001', -- ID do super admin
    true,
    'owner',
    true, -- Pode acessar eventos
    true, -- Pode acessar campanhas
    NOW(),
    NOW()
)
ON CONFLICT (id) DO NOTHING;

-- =====================================================
-- Módulos do Sistema
-- =====================================================

-- Insert do módulo de Eventos
INSERT INTO tb_app_modules (
    id,
    slug,
    module_name,
    module_description,
    module_active,
    created_at,
    updated_at
) VALUES (
    'abcd0000-0000-0000-0000-000000000005',
    'eventos',
    'Eventos',
    'Módulo de gerenciamento de eventos',
    true,
    NOW(),
    NOW()
)
ON CONFLICT (id) DO NOTHING;

-- Insert do módulo de Campanhas
INSERT INTO tb_app_modules (
    id,
    slug,
    module_name,
    module_description,
    module_active,
    created_at,
    updated_at
) VALUES (
    'abcd0000-0000-0000-0000-000000000006',
    'campanhas',
    'Campanhas',
    'Módulo de gerenciamento de campanhas',
    true,
    NOW(),
    NOW()
)
ON CONFLICT (id) DO NOTHING;

-- =====================================================
-- Associar Módulos ao Customer Padrão
-- =====================================================

-- Habilita módulo de Eventos para o customer EmpresaTeste
INSERT INTO tb_customers_app_modules (
    id,
    customer_id,
    module_id,
    created_at,
    updated_at
) VALUES (
    'abcd0000-0000-0000-0000-000000000007',
    'abcd0000-0000-0000-0000-000000000003', -- ID do customer EmpresaTeste
    'abcd0000-0000-0000-0000-000000000005', -- ID do módulo de Eventos
    NOW(),
    NOW()
)
ON CONFLICT (id) DO NOTHING;

-- Habilita módulo de Campanhas para o customer EmpresaTeste
INSERT INTO tb_customers_app_modules (
    id,
    customer_id,
    module_id,
    created_at,
    updated_at
) VALUES (
    'abcd0000-0000-0000-0000-000000000008',
    'abcd0000-0000-0000-0000-000000000003', -- ID do customer EmpresaTeste
    'abcd0000-0000-0000-0000-000000000006', -- ID do módulo de Campanhas
    NOW(),
    NOW()
)
ON CONFLICT (id) DO NOTHING;

-- =====================================================
-- CREDENCIAIS DE ACESSO PADRÃO:
-- Email: admin@empresateste.com
-- Senha: admin123
--
-- IMPORTANTE: Altere a senha após o primeiro login!
-- =====================================================

-- =====================================================
-- ESTRUTURA CRIADA:
-- 1. APP EmpresaTeste (white label principal)
-- 2. Usuário Super Administrador (owner do APP)
-- 3. Customer Padrão "EmpresaTeste" (conta administrativa)
-- 4. Associação do super admin ao customer padrão
-- 5. Módulos do Sistema (Eventos e Campanhas)
-- 6. Módulos habilitados para o Customer padrão
--
-- O super admin tem acesso total:
-- - Como OWNER do APP (pode gerenciar configurações, criar novos customers)
-- - Como ADMIN do Customer padrão (pode criar eventos e campanhas)
-- =====================================================
