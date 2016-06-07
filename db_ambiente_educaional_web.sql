--
-- PostgreSQL database dump
--

-- Dumped from database version 9.1.18
-- Dumped by pg_dump version 9.1.18
-- Started on 2016-06-06 10:48:58 BRT

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 304 (class 3079 OID 11651)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2929 (class 0 OID 0)
-- Dependencies: 304
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- TOC entry 637 (class 1247 OID 114486)
-- Dependencies: 6 161
-- Name: tipo_busca_conteudo; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE tipo_busca_conteudo AS (
	id integer,
	nome text,
	descricao text,
	idfoto text,
	tipo text,
	idusuario integer,
	nome_dono text,
	datacriacao timestamp without time zone,
	idcomunidade integer,
	nome_comunidade text,
	ordem integer
);


ALTER TYPE public.tipo_busca_conteudo OWNER TO postgres;

--
-- TOC entry 640 (class 1247 OID 114489)
-- Dependencies: 6 162
-- Name: tipo_feed_espacoaberto; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE tipo_feed_espacoaberto AS (
	id integer,
	idusuarioremetente integer,
	idusuariodestinatario integer,
	idfeedtabela integer,
	idfeedmensagem integer,
	idregistrotabela integer,
	datacriacao timestamp without time zone,
	mensagem character varying(2000),
	valorantigo character varying(150),
	valornovo character varying(150)
);


ALTER TYPE public.tipo_feed_espacoaberto OWNER TO postgres;

--
-- TOC entry 643 (class 1247 OID 114492)
-- Dependencies: 6 163
-- Name: tipo_mensagem_chat; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE tipo_mensagem_chat AS (
	id integer,
	id_de integer,
	id_para integer,
	mensagem character varying(500),
	data timestamp without time zone,
	lido boolean,
	nome character varying(150),
	tempo_minuto integer,
	tempo_segundo integer
);


ALTER TYPE public.tipo_mensagem_chat OWNER TO postgres;

--
-- TOC entry 646 (class 1247 OID 114495)
-- Dependencies: 6 164
-- Name: tipo_usuario_chat; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE tipo_usuario_chat AS (
	idusuario integer,
	nomeusuario character varying(150),
	status integer,
	idusuariofoto integer,
	extensao character varying(5),
	nome_semacento character varying(150),
	sobremim character varying(500),
	online integer,
	bloqueado integer,
	dataultima timestamp without time zone,
	iddispositivo integer,
	nomedispositivo character varying(200)
);


ALTER TYPE public.tipo_usuario_chat OWNER TO postgres;

--
-- TOC entry 316 (class 1255 OID 114496)
-- Dependencies: 961 6
-- Name: apagar_usuario(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION apagar_usuario() RETURNS trigger
    LANGUAGE plpgsql
    AS $$BEGIN
	IF TG_OP != 'DELETE' THEN
		RAISE EXCEPTION 'Erro no tipo de ação executada';
	END IF;

	IF OLD.flativo = true THEN
		RAISE EXCEPTION 'Usuário encontra-se ativo. Não pode ser excluído';
	END IF;
	
	/*---- Limpa registros relacionados do usuario ---*/
	DELETE FROM chatmensagens WHERE (chatmensagens.id_de = OLD.idusuario OR chatmensagens.id_para = OLD.idusuario) AND OLD.flativo = false;
	DELETE FROM chatmensagensstatus WHERE (chatmensagensstatus.id_de = OLD.idusuario OR chatmensagensstatus.id_para = OLD.idusuario) AND OLD.flativo = false;
	DELETE FROM comunidadesugerida WHERE (comunidadesugerida.idusuarioconvite = OLD.idusuario OR comunidadesugerida.idusuario = OLD.idusuario) AND OLD.flativo = false;
	DELETE FROM comuusuario WHERE comuusuario.flmoderador = false AND comuusuario.idusuario = OLD.idusuario AND OLD.flativo = false;
	DELETE FROM comuvoto WHERE comuvoto.idusuario = OLD.idusuario AND OLD.flativo = false;
	DELETE FROM conteudodigitalcomentario WHERE conteudodigitalcomentario.idusuario = OLD.idusuario AND OLD.flativo = false;
	DELETE FROM conteudodigitalvoto WHERE conteudodigitalvoto.idusuario = OLD.idusuario AND OLD.flativo = false;
	DELETE FROM denuncia WHERE denuncia.idusuario = OLD.idusuario AND OLD.flativo = false;
	DELETE FROM feedcontagem WHERE feedcontagem.idusuario = OLD.idusuario AND OLD.flativo = false;
	DELETE FROM feeddetalhe WHERE (feeddetalhe.idusuariodestinatario = OLD.idusuario OR feeddetalhe.idusuarioremetente = OLD.idusuario) AND OLD.flativo = false;
	DELETE FROM usuarioagenda WHERE usuarioagenda.idusuario = OLD.idusuario AND OLD.flativo = false;
	DELETE FROM usuarioalbumfoto USING usuarioalbum WHERE usuarioalbum.idusuario = OLD.idusuario AND OLD.flativo = false AND usuarioalbum.idusuarioalbum = usuarioalbumfoto.idusuarioalbum;
	DELETE FROM usuarioalbum WHERE usuarioalbum.idusuario = OLD.idusuario AND OLD.flativo = false;
	DELETE FROM usuariofoto WHERE usuariofoto.idusuario = OLD.idusuario AND OLD.flativo = false;
	DELETE FROM usuariocolega WHERE usuariocolega.idusuario = OLD.idusuario AND OLD.flativo = false;
	DELETE FROM usuariocomponente WHERE usuariocomponente.idusuario = OLD.idusuario AND OLD.flativo = false;
	DELETE FROM usuariosobremimperfil WHERE usuariosobremimperfil.idusuario = OLD.idusuario AND OLD.flativo = false;
	DELETE FROM usuarioamigo WHERE (usuarioamigo.idusuario = OLD.idusuario OR usuarioamigo.idusuarioindicou = OLD.idusuario OR usuarioamigo.idusuarioaprovar = OLD.idusuario) AND OLD.flativo = false;
	
	/*--- Limpa relação de recados ---*/
	UPDATE	usuariorecado
	   SET	idrecadorelacionado = NULL
 	 WHERE	usuariorecado.idusuario = OLD.idusuario OR usuariorecado.idusuarioautor = OLD.idusuario;

	/*--- Limpa recados ---*/
	DELETE FROM usuariorecado WHERE (usuariorecado.idusuario = OLD.idusuario OR usuariorecado.idusuarioautor = OLD.idusuario) AND OLD.flativo = false;

	RETURN OLD;
END;$$;


ALTER FUNCTION public.apagar_usuario() OWNER TO postgres;

--
-- TOC entry 317 (class 1255 OID 114497)
-- Dependencies: 961 6
-- Name: atualizar_chat_mensagens_status(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION atualizar_chat_mensagens_status() RETURNS trigger
    LANGUAGE plpgsql
    AS $$BEGIN
   IF TG_OP = 'UPDATE' THEN
     IF EXISTS( SELECT * FROM chatmensagensstatus AS cm
		WHERE cm.id_de = NEW.idusuario AND cm.flavisar = TRUE) THEN
	IF NEW.flacesso = FALSE OR NEW.flchatativo = FALSE THEN
	   UPDATE chatmensagensstatus
	      SET flavisar = FALSE
	    WHERE id_de = NEW.idusuario;
        END IF;
     END IF;
   END IF;	
   RETURN NEW;
END;$$;


ALTER FUNCTION public.atualizar_chat_mensagens_status() OWNER TO postgres;

--
-- TOC entry 318 (class 1255 OID 114498)
-- Dependencies: 6 961
-- Name: atualizar_mensagem_chat_lidos(integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION atualizar_mensagem_chat_lidos(idusuario_remetente integer, idusuario_destinatario integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $_$DECLARE
   -- parametros
   var_de   INTEGER;
   var_para INTEGER;
BEGIN
   var_de   := $1;
   var_para := $2;

   IF EXISTS(SELECT * FROM chatmensagens WHERE id_de = var_de AND id_para = var_para AND lido = FALSE) THEN
      UPDATE chatmensagens SET lido = TRUE WHERE id_de = var_de AND id_para = var_para;
   END IF;
   RETURN TRUE;	
END;
$_$;


ALTER FUNCTION public.atualizar_mensagem_chat_lidos(idusuario_remetente integer, idusuario_destinatario integer) OWNER TO postgres;

--
-- TOC entry 319 (class 1255 OID 114499)
-- Dependencies: 6 961
-- Name: atualizar_mensagem_chat_status(integer, integer, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION atualizar_mensagem_chat_status(idusuario_remetente integer, idusuario_destinatario integer, stavisar boolean) RETURNS boolean
    LANGUAGE plpgsql
    AS $_$DECLARE
   -- parametros
   var_de     ALIAS FOR $1;
   var_para   ALIAS FOR $2;
   var_avisar ALIAS FOR $3;
BEGIN
   IF NOT EXISTS(SELECT * FROM chatmensagensstatus WHERE id_de = var_de AND id_para = var_para) THEN
      INSERT INTO chatmensagensstatus (id_de, id_para, flavisar) VALUES(var_de,var_para,var_avisar);
   ELSE
      UPDATE chatmensagensstatus SET flavisar = var_avisar WHERE id_de = var_de AND id_para = var_para;
   END IF;
   RETURN TRUE;	
END;
$_$;


ALTER FUNCTION public.atualizar_mensagem_chat_status(idusuario_remetente integer, idusuario_destinatario integer, stavisar boolean) OWNER TO postgres;

--
-- TOC entry 327 (class 1255 OID 114500)
-- Dependencies: 637 6 961
-- Name: consulta_busca_conteudo(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION consulta_busca_conteudo(texto_descricao character varying) RETURNS SETOF tipo_busca_conteudo
    LANGUAGE plpgsql
    AS $_$DECLARE
   var_busca CHARACTER VARYING;
   
   -- cursor
   reg tipo_busca_conteudo%ROWTYPE;
BEGIN
  var_busca := REPLACE($1,' ','%');
  var_busca := sem_acentos(LOWER(var_busca));
  FOR reg IN
	SELECT u.idusuario AS id
	      ,LOWER(u.nomeusuario)
	      ,pu.sobremim AS descricao
	      ,(CASE WHEN uf.idusuariofoto IS NULL THEN 'padrao.png' ELSE CAST(uf.idusuariofoto AS CHARACTER VARYING)||'.'||uf.extensao END) AS idfoto
	      ,'colega' AS tipo
	      ,0
	      ,''
	      ,u.datacriacao	
	      ,0
	      ,''
	      ,1		
	FROM  usuario u
	LEFT JOIN usuariosobremimperfil AS pu ON (pu.idusuario = u.idusuario)
	LEFT JOIN usuariofoto           AS uf ON (uf.idusuario = u.idusuario)
	WHERE (sem_acentos(LOWER(u.nomeusuario)) LIKE '%'||var_busca||'%' OR sem_acentos(LOWER(pu.sobremim)) LIKE '%'||var_busca||'%') AND u.flativo = TRUE 

	UNION ALL

	SELECT c.idcomunidade
	      ,LOWER(c.nomecomunidade)
	      ,c.descricao
	      ,(CASE WHEN cf.idcomunidadefoto IS NULL THEN 'padrao.png' ELSE CAST(cf.idcomunidadefoto AS CHARACTER VARYING)||'.'||cf.extensao END)
	      ,'comunidade'
	      ,c.idusuario	
	      ,LOWER(u.nomeusuario)
	      ,c.datacriacao
	      ,0
	      ,''
	      ,2
	FROM  comunidade AS c
	LEFT  JOIN comunidadefoto AS cf ON (cf.idcomunidade = c.idcomunidade)
	INNER JOIN usuario AS u ON (u.idusuario = c.idusuario)
	WHERE (sem_acentos(LOWER(c.nomecomunidade)) LIKE '%'||var_busca||'%' OR sem_acentos(LOWER(c.descricao)) LIKE '%'||var_busca||'%') AND ativa = TRUE

	UNION ALL

	SELECT ub.idusuarioblog
	      ,ub.titulo
	      ,ub.texto
	      ,'padrao.png'
	      ,'colega-blog'
	      ,ub.idusuario
	      ,LOWER(u.nomeusuario)
	      ,ub.datacriacao
	      ,0
	      ,''
	      ,3
	FROM  usuarioblog AS ub
	INNER JOIN usuario AS u ON (u.idusuario = ub.idusuario AND u.flativo = TRUE)
	WHERE sem_acentos(LOWER(u.nomeusuario)) LIKE '%'||var_busca||'%' OR sem_acentos(LOWER(ub.titulo)) LIKE '%'||var_busca||'%' OR sem_acentos(LOWER(ub.texto)) LIKE '%'||var_busca||'%'

	UNION ALL

	SELECT cb.idcomunidadeblog
	      ,cb.titulo
	      ,cb.texto
	      ,'padrao.png'
	      ,'comunidade-blog'
	      ,cb.idusuario
	      ,LOWER(u.nomeusuario)
	      ,cb.datacriacao
	      ,cb.idcomunidade
	      ,c.nomecomunidade
	      ,4
	FROM  comunidadeblog AS cb
	INNER JOIN usuario AS u ON (u.idusuario = cb.idusuario AND u.flativo = TRUE)
	INNER JOIN comunidade AS c ON (c.idcomunidade = cb.idcomunidade AND ativa = TRUE)
	WHERE sem_acentos(LOWER(cb.titulo)) LIKE '%'||var_busca||'%' OR sem_acentos(LOWER(cb.texto)) LIKE '%'||var_busca||'%' OR sem_acentos(LOWER(u.nomeusuario)) LIKE '%'||var_busca||'%'

	UNION ALL

	SELECT ct.idcomutopico
	      ,ct.titulo
	      ,ct.mensagem
	      ,'padrao.png'
	      ,'comunidade-forum'
	      ,ct.idusuario
	      ,LOWER(u.nomeusuario)
	      ,ct.datacriacao
	      ,ct.idcomunidade
	      ,c.nomecomunidade
	      ,5
	FROM  comutopico AS ct
	INNER JOIN usuario AS u ON (u.idusuario = ct.idusuario)
	INNER JOIN comunidade AS c ON (c.idcomunidade = ct.idcomunidade  AND ativa = TRUE)
	WHERE sem_acentos(LOWER(ct.titulo)) LIKE '%'||var_busca||'%' OR sem_acentos(LOWER(ct.mensagem)) LIKE '%'||var_busca||'%'

	UNION ALL

	SELECT cd.idconteudodigital
	      ,LOWER(cd.titulo)
	      ,cd.descricao
	      ,CASE f.idconteudotipo WHEN  1 THEN 'ico_documento_portal.png'
				     WHEN  2 THEN 'ico_planilha_portal.png'
				     WHEN  3 THEN 'ico_apresentacao_portal.png'
				     WHEN  4 THEN 'ico_audio_portal.png'
				     WHEN  5 THEN 'ico_video_portal.png'
				     WHEN  6 THEN 'ico_imagem_portal.png'
				     WHEN  7 THEN 'ico_animacao_portal.png'
				     WHEN  8 THEN 'ico_site_portal.png'
				     WHEN  9 THEN 'ico_software_educacional_portal.png'
				     WHEN 10 THEN 'ico_curso_portal.png'
	       END	
	      ,'conteudo-digital'
	      ,cd.idusuariopublicador
	      ,LOWER(u.nomeusuario)
	      ,cd.datapublicacao
	      ,NULL
	      ,NULL
	      ,6
	FROM  conteudodigital AS cd
	INNER JOIN usuario AS u ON (u.idusuario = cd.idusuariopublicador)
	INNER JOIN formato AS f ON (f.idformato = cd.idformato)
	INNER JOIN conteudotipo AS ct ON (ct.idconteudotipo = f.idconteudotipo)
	INNER JOIN conteudodigitalcomponente AS cdc ON(cdc.idconteudodigital = cd.idconteudodigital)
	INNER JOIN componentecurricular AS cc ON (cc.idcomponentecurricular = cdc.idcomponentecurricular)
	INNER JOIN nivelensino AS ne ON(ne.idnivelensino = cc.idnivelensino)
	WHERE sem_acentos(LOWER(cd.titulo)) LIKE '%'||var_busca||'%' OR sem_acentos(LOWER(cd.descricao)) LIKE '%'||var_busca||'%' OR sem_acentos(LOWER(cc.nomecomponentecurricular)) LIKE '%'||var_busca||'%' OR sem_acentos(LOWER(ne.nomenivelensino)) LIKE '%'||var_busca||'%'

	UNION ALL

	SELECT aa.idambientedeapoio
	      ,LOWER(aa.titulo)
	      ,aa.descricao
	      ,CAST(aa.idambientedeapoio AS CHARACTER VARYING)||'.png'
	      ,'ambiente-apoio'
	      ,NULL
	      ,NULL
	      ,NULL
	      ,NULL
	      ,NULL
	      ,7
	FROM  ambientedeapoio AS aa
	WHERE sem_acentos(LOWER(aa.titulo)) LIKE '%'||var_busca||'%' OR sem_acentos(LOWER(aa.descricao)) LIKE '%'||var_busca||'%'

	ORDER BY 11, 2
  LOOP
     RETURN NEXT reg;
  END LOOP;  
END;
$_$;


ALTER FUNCTION public.consulta_busca_conteudo(texto_descricao character varying) OWNER TO postgres;

--
-- TOC entry 323 (class 1255 OID 114501)
-- Dependencies: 961 640 6
-- Name: consultar_feed_espacoaberto(integer, integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION consultar_feed_espacoaberto(idusuario integer, idfeed_min integer, idfeed_max integer) RETURNS SETOF tipo_feed_espacoaberto
    LANGUAGE plpgsql
    AS $_$DECLARE
   -- parametros
   var_usuario  ALIAS FOR $1;
   var_feed_min ALIAS FOR $2;
   var_feed_max ALIAS FOR $3;

   -- variavel local
   var_nomeusuario     CHARACTER VARYING(150);
   var_descricao       CHARACTER VARYING(250);
   var_url_perfil      CHARACTER VARYING(500);
   var_buscanome       BOOLEAN;
   var_buscacomunidade BOOLEAN;
   var_buscacolega     BOOLEAN;
   var_buscablog       BOOLEAN;
   var_buscaalbum      BOOLEAN;
   var_buscaconteudo   BOOLEAN;
   var_buscatopico     BOOLEAN;
   var_buscaagenda     BOOLEAN;   
   var_tipo            INTEGER;
   var_registro        INTEGER;
   
   -- cursor
   reg tipo_feed_espacoaberto%ROWTYPE;
  
BEGIN 

   var_buscacolega := FALSE;
   IF EXISTS(SELECT * FROM usuariocolega AS uc WHERE (uc.idusuario = var_usuario OR uc.idusuariocolega = var_usuario) AND uc.flativocolega = TRUE) THEN
      var_buscacolega := TRUE;
   END IF;

   -- Filtra feed por usuários e colegas (se possuir)
   FOR reg IN
   SELECT DISTINCT feed.id
	 ,feed.idusuarioremetente
	 ,feed.idusuariodestinatario
	 ,feed.idfeedtabela
	 ,feed.idfeedmensagem
	 ,feed.idregistrotabela
	 ,feed.datacriacao
	 ,''
	 ,feed.valorantigo
	 ,feed.valornovo
   FROM feeddetalhe AS feed
   WHERE (feed.idusuarioremetente = var_usuario OR feed.idusuariodestinatario = var_usuario)
   AND   (var_feed_min = 0 OR feed.id < var_feed_min)
   AND   (var_feed_max = 0 OR feed.id > var_feed_max)
   AND   NOT EXISTS(
		SELECT *
		FROM   feeddetalhe AS fd
		WHERE  fd.id = feed.id
		AND    fd.idusuariodestinatario <> var_usuario
		AND    (fd.idfeedmensagem = 5 OR fd.idfeedmensagem = 6 OR fd.idfeedmensagem = 8 OR fd.idfeedmensagem = 10 OR fd.idfeedmensagem = 22))
   UNION
   SELECT DISTINCT feed.id
	 ,feed.idusuarioremetente
	 ,feed.idusuariodestinatario
	 ,feed.idfeedtabela
	 ,feed.idfeedmensagem
	 ,feed.idregistrotabela
	 ,feed.datacriacao
	 ,''
	 ,feed.valorantigo
	 ,feed.valornovo
   FROM feeddetalhe AS feed
   INNER JOIN (SELECT CASE WHEN uc.idusuario = var_usuario THEN uc.idcolega ELSE uc.idusuario END AS idusuario_colega FROM usuariocolega AS uc WHERE (uc.idusuario = var_usuario OR uc.idcolega = var_usuario) AND flativocolega = TRUE) AS ur ON (feed.idusuarioremetente    = ur.idusuario_colega)
   INNER JOIN (SELECT CASE WHEN uc.idusuario = var_usuario THEN uc.idcolega ELSE uc.idusuario END AS idusuario_colega FROM usuariocolega AS uc WHERE (uc.idusuario = var_usuario OR uc.idcolega = var_usuario) AND flativocolega = TRUE) AS ud ON (feed.idusuariodestinatario = ud.idusuario_colega)
   WHERE (var_buscacolega = TRUE)
   AND   (var_feed_min = 0 OR feed.id < var_feed_min)
   AND   (var_feed_max = 0 OR feed.id > var_feed_max)
   AND   NOT EXISTS(
		SELECT *
		FROM   feeddetalhe AS fd
		WHERE  fd.id = feed.id
		AND    (fd.idfeedmensagem = 5 OR fd.idfeedmensagem = 6 OR fd.idfeedmensagem = 8 OR fd.idfeedmensagem = 10 OR fd.idfeedmensagem = 22))
   ORDER BY 7 DESC
   LOOP
       var_buscacomunidade := FALSE; 
       var_buscanome       := FALSE;
       var_buscablog       := FALSE;
       var_buscaalbum      := FALSE;
       var_buscaconteudo   := FALSE;
       var_buscatopico     := FALSE;
       var_buscaagenda     := FALSE;
  	
       reg.mensagem   := (SELECT nomefeedtipo FROM feedtipo WHERE id = reg.idfeedmensagem);

       IF position('[idusuarioremetente]' IN reg.mensagem)>0 OR position('[idusuariodestinatario]' IN reg.mensagem)>0 THEN
          var_buscanome := TRUE;
       END IF;

       IF position('[idcomunidade]' IN reg.mensagem)>0 THEN
          var_buscacomunidade := TRUE;
       END IF;

       IF position('[idblog]' IN reg.mensagem)>0 THEN
	  var_buscablog := TRUE;
       END IF;

       IF position('[idalbum]' IN reg.mensagem)>0 THEN
	  var_buscaalbum := TRUE;
       END IF;
	
       IF position('[idconteudo]' IN reg.mensagem)>0 THEN
	  var_buscaconteudo := TRUE;
       END IF;

       IF position('[idtopico]' IN reg.mensagem)>0 THEN
	  var_buscatopico := TRUE;
       END IF;

       IF position('[idagenda]' IN reg.mensagem)>0 THEN
	  var_buscaagenda := TRUE;
       END IF;

       IF var_buscanome = TRUE THEN
       
	  var_url_perfil := '<a title="Visualizar perfil de [nome]" href="/espaco-aberto/perfil/feed/usuario/[id]">[nome]</a>';
          IF reg.idusuarioremetente IS NOT NULL AND position('[idusuarioremetente]' IN reg.mensagem)>0 THEN
             var_nomeusuario := (SELECT a.nomeusuario FROM usuario AS a WHERE a.idusuario = reg.idusuarioremetente AND a.flativo = TRUE);
             var_url_perfil := replace(var_url_perfil,'[id]',CAST(reg.idusuarioremetente AS TEXT));
             var_url_perfil := replace(var_url_perfil,'[nome]',var_nomeusuario);
             reg.mensagem := replace(reg.mensagem,'[idusuarioremetente]',var_url_perfil);
          END IF;
          
          var_url_perfil := '<a title="Visualizar perfil de [nome]" href="/espaco-aberto/perfil/feed/usuario/[id]">[nome]</a>';
          IF reg.idusuariodestinatario IS NOT NULL AND position('[idusuariodestinatario]' IN reg.mensagem)>0 THEN
             var_nomeusuario := (SELECT b.nomeusuario FROM usuario AS b WHERE b.idusuario = reg.idusuariodestinatario AND b.flativo = TRUE);
             var_url_perfil := replace(var_url_perfil,'[id]',CAST(reg.idusuariodestinatario AS TEXT));
             var_url_perfil := replace(var_url_perfil,'[nome]',var_nomeusuario);
             reg.mensagem := replace(reg.mensagem,'[idusuariodestinatario]',var_url_perfil);
          END IF;

       END IF;

       IF var_buscablog = TRUE THEN
       
          IF reg.idfeedtabela = 2 THEN
	     
	     var_tipo = (SELECT tipo FROM blogcomentario WHERE idblogcomentario = reg.idregistrotabela);
	     IF var_tipo = 1 THEN
	        var_url_perfil := '<a title="Ir para o blog" href="/espaco-aberto/blog/exibir/usuario/[id]/id/[blog]">[descricao]</a>'; 
	        var_descricao = (SELECT ub.titulo FROM blogcomentario AS bc INNER JOIN usuarioblog AS ub ON (bc.idblog = ub.idusuarioblog) WHERE bc.idblogcomentario = reg.idregistrotabela);
	     ELSE
	        var_url_perfil := '<a title="Ir para o blog" href="/espaco-aberto/blog/exibir/comunidade/[id]/id/[blog]">[descricao]</a>';
	        var_descricao = (SELECT cb.titulo FROM blogcomentario AS bc INNER JOIN comunidadeblog AS cb ON (bc.idblog = cb.idcomunidadeblog) WHERE bc.idblogcomentario = reg.idregistrotabela); 	
	     END IF;

	     IF var_tipo = 1 THEN	
	        IF reg.idfeedmensagem = 24 THEN
	           var_url_perfil := replace(var_url_perfil,'[id]',CAST(reg.idusuarioremetente AS TEXT));
	        ELSE
	           var_url_perfil := replace(var_url_perfil,'[id]',CAST(reg.idusuariodestinatario AS TEXT));
	        END IF;
	     ELSE
	        var_tipo       := (SELECT cb.idcomunidade FROM blogcomentario AS bc INNER JOIN comunidadeblog AS cb ON (cb.idcomunidadeblog = bc.idblog) WHERE idblogcomentario = reg.idregistrotabela);
	        var_url_perfil := replace(var_url_perfil,'[id]',CAST(var_tipo AS TEXT));
	     END IF;
	     
	     var_tipo       := (SELECT idblog FROM blogcomentario WHERE idblogcomentario = reg.idregistrotabela);   
	     var_url_perfil := replace(var_url_perfil,'[blog]',CAST(var_tipo AS TEXT));
	     var_url_perfil := replace(var_url_perfil,'[descricao]',var_descricao);
          END IF;	    
			
          IF reg.idfeedtabela = 5 THEN
             var_url_perfil := '<a title="Ir para o blog" href="/espaco-aberto/blog/exibir/comunidade/[id]/id/[blog]">[descricao]</a>';
	     var_descricao = (SELECT cb.titulo FROM comunidadeblog AS cb WHERE cb.idcomunidadeblog = reg.idregistrotabela);
	     var_url_perfil := replace(var_url_perfil,'[id]',CAST(reg.idusuarioremetente AS TEXT));
	     var_url_perfil := replace(var_url_perfil,'[blog]',CAST(reg.idregistrotabela AS TEXT));
	     var_url_perfil := replace(var_url_perfil,'[descricao]',var_descricao);
	  END IF;
          
          IF reg.idfeedtabela = 12 THEN
             var_url_perfil := '<a title="Ir para o blog" href="/espaco-aberto/blog/exibir/usuario/[id]/id/[blog]">[descricao]</a>';
	     var_descricao = (SELECT ub.titulo FROM usuarioblog AS ub WHERE ub.idusuarioblog = reg.idregistrotabela);
	     var_url_perfil := replace(var_url_perfil,'[id]',CAST(reg.idusuarioremetente AS TEXT));
	     var_url_perfil := replace(var_url_perfil,'[blog]',CAST(reg.idregistrotabela AS TEXT));
	     var_url_perfil := replace(var_url_perfil,'[descricao]',var_descricao);
	  END IF;

	  reg.mensagem   := replace(reg.mensagem,'[idblog]',var_url_perfil);
       END IF;

       IF var_buscacomunidade = TRUE THEN
	  var_url_perfil := '<a title="Ir para a comunidade" href="/espaco-aberto/comunidade/exibir/comunidade/[id]">[descricao]</a>';
	  var_tipo := 0;
/*
          IF reg.idfeedtabela = 1 THEN
             var_tipo := (SELECT c.idcomunidade FROM ambientedeapoiofavorito AS af INNER JOIN comunidade AS c ON (c.idfavorito = af.idfavorito) WHERE af.idambientedeapoio = reg.idregistrotabela AND reg.idfeedmensagem = 13);
	  END IF;
*/
	  IF reg.idfeedtabela = 2 THEN
	     var_tipo := (SELECT cb.idcomunidade FROM blogcomentario AS bc INNER JOIN comunidadeblog AS cb ON (bc.idblog = cb.idcomunidadeblog) WHERE bc.idblogcomentario = reg.idregistrotabela);
	  END IF;

	  IF reg.idfeedtabela = 3 THEN
	     var_tipo := (SELECT idcomunidade FROM comunidadealbum WHERE idcomunidadealbum = reg.idregistrotabela);
	  END IF;
	  
	  IF reg.idfeedtabela = 4 THEN
	     var_tipo := (SELECT ca.idcomunidade FROM comunidadealbumfoto AS caf INNER JOIN comunidadealbum AS ca ON (caf.idcomunidadealbum = ca.idcomunidadealbum) WHERE caf.idcomunidadealbumfoto = reg.idregistrotabela);
	  END IF;

          IF reg.idfeedtabela = 5 THEN
             var_tipo := (SELECT cb.idcomunidade FROM comunidadeblog AS cb WHERE cb.idcomunidadeblog = reg.idregistrotabela);
	  END IF;

          IF reg.idfeedtabela = 6 THEN
             var_tipo := reg.idregistrotabela;
	  END IF;
/*
          IF reg.idfeedtabela = 7 THEN
             var_tipo := (SELECT c.idcomunidade FROM conteudodigitalfavorito AS cf INNER JOIN comunidade AS c ON (c.idfavorito = cf.idfavorito) WHERE cf.idconteudodigital = reg.idregistrotabela AND reg.idfeedmensagem = 13);
	  END IF;
*/
          IF reg.idfeedtabela = 8 THEN
             var_tipo := (SELECT idcomunidade FROM enquete WHERE idenquete = reg.idregistrotabela);
	  END IF;

          IF reg.idfeedtabela = 9 THEN
             var_tipo := (SELECT idcomunidade FROM comunidadeagenda WHERE idcomunidadeagenda = reg.idregistrotabela);
	  END IF;

          IF reg.idfeedtabela = 16 THEN
             var_tipo := (SELECT idcomunidade FROM comunidadeagenda WHERE idcomunidadeagenda = reg.idregistrotabela);
	  END IF;

          IF reg.idfeedtabela = 17 THEN
             var_tipo := (SELECT idcomunidade FROM comuusuario WHERE idcomuusuario = reg.idregistrotabela);
	  END IF;
	  
          IF reg.idfeedtabela = 18 THEN
             var_tipo := (SELECT idcomunidade FROM comutopico WHERE idcomutopico = reg.idregistrotabela);
	  END IF;

          IF reg.idfeedtabela = 21 THEN
             var_tipo := reg.idregistrotabela;
	  END IF;

	  IF var_tipo>0 THEN
	     var_descricao = (SELECT nomecomunidade FROM comunidade WHERE idcomunidade = var_tipo);
	     var_url_perfil := replace(var_url_perfil,'[id]',CAST(var_tipo AS TEXT));
	     var_url_perfil := replace(var_url_perfil,'[descricao]',var_descricao);
	     reg.mensagem   := replace(reg.mensagem,'[idcomunidade]',var_url_perfil);
	  END IF;
       END IF;

       IF var_buscaalbum = TRUE THEN
	  var_descricao := '';

          IF reg.idfeedtabela = 3 OR reg.idfeedtabela = 4 THEN
             var_url_perfil := '<a title="Ir para o álbum" href="/espaco-aberto/album/exibir/comunidade/[id]/id/[idalbum]">[descricao]</a>';

             IF reg.idfeedtabela = 3 THEN
                var_tipo      := (SELECT idcomunidade FROM comunidadealbum WHERE idcomunidadealbum = reg.idregistrotabela);
	        var_descricao := (SELECT titulo FROM comunidadealbum WHERE idcomunidadealbum = reg.idregistrotabela);
	        var_registro  := reg.idregistrotabela;
	     END IF;
	         
             IF reg.idfeedtabela = 4 THEN
                var_tipo       := (SELECT ca.idcomunidade FROM comunidadealbumfoto AS caf INNER JOIN comunidadealbum AS ca ON (ca.idcomunidadealbum = caf.idcomunidadealbum)  WHERE caf.idcomunidadealbumfoto = reg.idregistrotabela);
                var_registro   := (SELECT ca.idcomunidadealbum FROM comunidadealbumfoto AS caf INNER JOIN comunidadealbum AS ca ON (ca.idcomunidadealbum = caf.idcomunidadealbum)  WHERE caf.idcomunidadealbumfoto = reg.idregistrotabela);
	        var_descricao  := (SELECT ca.titulo FROM comunidadealbumfoto AS caf INNER JOIN comunidadealbum AS ca ON (ca.idcomunidadealbum = caf.idcomunidadealbum)  WHERE caf.idcomunidadealbumfoto = reg.idregistrotabela);
	     END IF;

          END IF;

          IF reg.idfeedtabela = 10 OR reg.idfeedtabela = 11 THEN
             var_url_perfil := '<a title="Ir para o álbum" href="/espaco-aberto/album/exibir/usuario/[id]/id/[idalbum]">[descricao]</a>';

             IF reg.idfeedtabela = 10 THEN
                var_tipo      := (SELECT ua.idusuario FROM usuarioalbum AS ua WHERE ua.idusuarioalbum = reg.idregistrotabela);
	        var_descricao := (SELECT ua.titulo FROM usuarioalbum AS ua WHERE ua.idusuarioalbum = reg.idregistrotabela);
	        var_registro  := reg.idregistrotabela; 
             END IF;

             IF reg.idfeedtabela = 11 THEN
                var_tipo       := (SELECT ua.idusuario FROM usuarioalbumfoto AS uaf INNER JOIN usuarioalbum AS ua ON (ua.idusuarioalbum = uaf.idusuarioalbum)  WHERE uaf.idusuarioalbumfoto = reg.idregistrotabela);
                var_registro   := (SELECT ua.idusuarioalbum FROM usuarioalbumfoto AS uaf INNER JOIN usuarioalbum AS ua ON (ua.idusuarioalbum = uaf.idusuarioalbum)  WHERE uaf.idusuarioalbumfoto = reg.idregistrotabela);
	        var_descricao  := (SELECT ua.titulo FROM usuarioalbumfoto AS uaf INNER JOIN usuarioalbum AS ua ON (ua.idusuarioalbum = uaf.idusuarioalbum)  WHERE uaf.idusuarioalbumfoto = reg.idregistrotabela);
             END IF;
             
          END IF;
          
	  IF var_descricao != '' THEN
	     var_url_perfil := replace(var_url_perfil,'[id]',CAST(var_tipo AS TEXT));
	     var_url_perfil := replace(var_url_perfil,'[idalbum]',CAST(var_registro AS TEXT));
	     var_url_perfil := replace(var_url_perfil,'[descricao]',var_descricao);
	     reg.mensagem   := replace(reg.mensagem,'[idalbum]',var_url_perfil);
	  END IF;

       END IF;

       IF var_buscaconteudo = TRUE THEN
	  var_descricao := '';

          IF reg.idfeedtabela = 1 THEN
             var_url_perfil := '<a title="Ir para o conteúdo" href="/ambientes-de-apoio/ambiente/exibir/id/[id]">[descricao]</a>';
             var_descricao  := (SELECT titulo FROM ambientedeapoio WHERE idambientedeapoio = reg.idregistrotabela);
             var_registro   := reg.idregistrotabela;
          END IF;

          IF reg.idfeedtabela = 7 THEN
             var_url_perfil := '<a title="Ir para o conteúdo" href="/conteudos-digitais/conteudo/exibir/id/[id]">[descricao]</a>';
             var_descricao  := (SELECT titulo FROM conteudodigital WHERE idconteudodigital = reg.idregistrotabela);
             var_registro   := reg.idregistrotabela;
          END IF;
          
	  IF var_descricao != '' THEN
	     var_url_perfil := replace(var_url_perfil,'[id]',CAST(var_registro AS TEXT));
	     var_url_perfil := replace(var_url_perfil,'[descricao]',var_descricao);
	     reg.mensagem   := replace(reg.mensagem,'[idconteudo]',var_url_perfil);
	  END IF;

       END IF;

       IF var_buscatopico = TRUE THEN
	  var_descricao := '';

          IF reg.idfeedtabela = 18 THEN
             var_url_perfil := '<a title="Ir para o tópico" href="/espaco-aberto/forum/exibir/comunidade/[id]/id/[idtopico]">[descricao]</a>';
             var_tipo       := (SELECT idcomunidade FROM comutopico WHERE idcomutopico = reg.idregistrotabela);
             var_descricao  := (SELECT titulo FROM comutopico WHERE idcomutopico = reg.idregistrotabela);
             var_registro   := reg.idregistrotabela;
          END IF;
          
	  IF var_descricao != '' THEN
	     var_url_perfil := replace(var_url_perfil,'[id]',CAST(var_tipo AS TEXT));
	     var_url_perfil := replace(var_url_perfil,'[idtopico]',CAST(var_registro AS TEXT));
	     var_url_perfil := replace(var_url_perfil,'[descricao]',var_descricao);
	     reg.mensagem   := replace(reg.mensagem,'[idtopico]',var_url_perfil);
	  END IF;
	  
       END IF;
              
       IF var_buscaagenda = TRUE THEN
	  var_descricao := '';

	  IF reg.idfeedtabela = 9 THEN
	     IF reg.idfeedmensagem = 10 OR reg.idfeedmensagem = 11 THEN
                var_url_perfil := '<a title="Ir para o evento" href="/espaco-aberto/agenda/exibir/id/[idagenda]/usuario/[id]">[descricao]</a>';
                var_descricao  := (SELECT evento FROM usuarioagenda WHERE idusuarioagenda = reg.idregistrotabela);
                var_registro   := reg.idregistrotabela;
                var_tipo       := reg.idusuarioremetente;
	     END IF;
	  
	     IF reg.idfeedmensagem = 22 OR reg.idfeedmensagem = 23 THEN
                var_url_perfil := '<a title="Ir para o evento" href="/espaco-aberto/agenda/exibir/id/[idagenda]/comunidade/[id]">[descricao]</a>';
                var_descricao  := (SELECT evento FROM comunidadeagenda WHERE idcomunidadeagenda = reg.idregistrotabela);
                var_tipo       := (SELECT idcomunidade FROM comunidadeagenda WHERE idcomunidadeagenda = reg.idregistrotabela);
                var_registro   := reg.idregistrotabela;
	     END IF;

	  END IF;
	  
          IF reg.idfeedtabela = 15 THEN
             var_url_perfil := '<a title="Ir para o evento" href="/espaco-aberto/agenda/exibir/id/[idagenda]/usuario/[id]">[descricao]</a>';
             var_descricao  := (SELECT evento FROM usuarioagenda WHERE idusuarioagenda = reg.idregistrotabela);
             var_registro   := reg.idregistrotabela;
             var_tipo       := reg.idusuarioremetente;
          END IF;
          
          IF reg.idfeedtabela = 16 THEN
             var_url_perfil := '<a title="Ir para o evento" href="/espaco-aberto/agenda/exibir/id/[idagenda]/comunidade/[id]">[descricao]</a>';
             var_descricao  := (SELECT evento FROM comunidadeagenda WHERE idcomunidadeagenda = reg.idregistrotabela);
             var_tipo       := (SELECT idcomunidade FROM comunidadeagenda WHERE idcomunidadeagenda = reg.idregistrotabela);
             var_registro   := reg.idregistrotabela;
          END IF;

	  IF var_descricao != '' THEN 
	     var_url_perfil := replace(var_url_perfil,'[id]',CAST(var_tipo AS TEXT));
	     var_url_perfil := replace(var_url_perfil,'[idagenda]',CAST(var_registro AS TEXT));
	     var_url_perfil := replace(var_url_perfil,'[descricao]',var_descricao);
	     reg.mensagem   := replace(reg.mensagem,'[idagenda]',var_url_perfil);
	  END IF;
	  
       END IF;

       RETURN NEXT reg;
   END LOOP;                
END;$_$;


ALTER FUNCTION public.consultar_feed_espacoaberto(idusuario integer, idfeed_min integer, idfeed_max integer) OWNER TO postgres;

--
-- TOC entry 321 (class 1255 OID 114503)
-- Dependencies: 6 643 961
-- Name: consultar_mensagem_chat(integer, integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION consultar_mensagem_chat(idusuario_remetente integer, idusuario_destinatario integer, id integer) RETURNS SETOF tipo_mensagem_chat
    LANGUAGE plpgsql
    AS $_$DECLARE
   -- parametros
   var_usuario_remetente    ALIAS FOR $1;
   var_usuario_destinatario ALIAS FOR $2;
   var_id_mensagem          ALIAS FOR $3;
   
   -- cursor 
   reg tipo_mensagem_chat%ROWTYPE;
  
BEGIN 	
   -- Filtra usuarios para chat
   FOR reg IN
	SELECT m.id
	      ,m.id_de
	      ,m.id_para
	      ,m.mensagem
	      ,m.data
	      ,m.lido
	      ,LOWER(u.nomeusuario) AS nome
	      ,CAST(EXTRACT('EPOCH' FROM DATE_TRUNC('minute',m.data)) AS INTEGER)
	      ,CAST(EXTRACT('EPOCH' FROM DATE_TRUNC('second',m.data)) AS INTEGER)
	FROM chatmensagens AS m
	INNER JOIN usuario AS u ON (u.idusuario = m.id_de)
	WHERE ((m.id_de = idusuario_remetente AND m.id_para = var_usuario_destinatario)
	OR    (m.id_de = var_usuario_destinatario AND m.id_para = var_usuario_remetente))
	AND   (CAST(EXTRACT(EPOCH FROM DATE_TRUNC('second',m.data)) AS INTEGER) > var_id_mensagem OR var_id_mensagem = 0)
	ORDER BY m.data ASC
	LOOP
	   RETURN NEXT reg;
	END LOOP; 
END;$_$;


ALTER FUNCTION public.consultar_mensagem_chat(idusuario_remetente integer, idusuario_destinatario integer, id integer) OWNER TO postgres;

--
-- TOC entry 325 (class 1255 OID 114504)
-- Dependencies: 6 961
-- Name: consultar_mensagem_chat_status(integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION consultar_mensagem_chat_status(idusuario_remetente integer, idusuario_destinatario integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $_$DECLARE
   -- parametros
   var_de     ALIAS FOR $1;
   var_para   ALIAS FOR $2;
   var_avisar BOOLEAN; 
BEGIN
   var_avisar := (SELECT flavisar FROM chatmensagensstatus WHERE id_de = var_de AND id_para = var_para);
   RETURN var_avisar;
END;
$_$;


ALTER FUNCTION public.consultar_mensagem_chat_status(idusuario_remetente integer, idusuario_destinatario integer) OWNER TO postgres;

--
-- TOC entry 320 (class 1255 OID 114505)
-- Dependencies: 646 961 6
-- Name: consultar_usuario_chat(integer, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION consultar_usuario_chat(idusuario integer, acesso boolean DEFAULT NULL::boolean) RETURNS SETOF tipo_usuario_chat
    LANGUAGE plpgsql
    AS $_$DECLARE
   -- parametros
   var_usuario  INTEGER;
   var_status   BOOLEAN;
   
   -- cursor
   reg tipo_usuario_chat%ROWTYPE;
  
BEGIN 
   var_usuario := $1;
   var_status  := $2;
   
   -- Filtra usuarios para chat
   FOR reg IN
	SELECT	 uc.idcolega
		,LOWER(u.nomeusuario) AS nome
		,CASE WHEN fc.flacesso IS NULL THEN 0 ELSE CAST(fc.flacesso AS INTEGER) END AS status
		,CASE WHEN uf.idusuariofoto IS NULL THEN NULL ELSE uf.idusuariofoto END AS idusuariofoto
		,CASE WHEN uf.idusuariofoto IS NULL THEN NULL ELSE uf.extensao END AS extensao		
		,(SELECT * FROM sem_acentos(LOWER(u.nomeusuario))) AS nome_semacento
		,up.sobremim
		,CASE WHEN fc.flacesso IS NULL OR CAST(fc.flacesso AS INTEGER) = 0 THEN 0
		 ELSE 
		     CASE WHEN sm.flbloquear = TRUE THEN 0
		     ELSE
		         CASE WHEN fc.flchatativo IS NULL THEN 0
		         ELSE CAST(fc.flchatativo AS INTEGER)
		         END
		     END
	         END AS online
		,CASE WHEN sb.flbloquear IS NULL THEN 0 ELSE CAST(sb.flbloquear AS INTEGER) END AS bloqueado
		,CASE WHEN sb.dataultimamensagem IS NULL THEN CURRENT_TIMESTAMP - INTERVAL '365 DAYS' ELSE sb.dataultimamensagem END
		,d.iddispositivo
		,d.nomedispositivo
	FROM usuariocolega AS uc 
	INNER JOIN usuario AS u ON (u.idusuario = uc.idcolega AND u.flativo = true)
	LEFT JOIN  usuariofoto AS uf ON (uf.idusuario = u.idusuario)
	LEFT JOIN  feedcontagem AS fc ON (fc.idusuario = u.idusuario)
	LEFT JOIN  dispositivo AS d ON(d.iddispositivo = fc.iddispositivo)
	LEFT JOIN  usuariosobremimperfil AS up ON (up.idusuario = u.idusuario)
	LEFT JOIN  chatmensagensstatus AS sm ON(sm.id_de = var_usuario AND sm.id_para = u.idusuario)
	LEFT JOIN  chatmensagensstatus AS sb ON(sb.id_de = u.idusuario AND sb.id_para = var_usuario)
	WHERE uc.idusuario = var_usuario AND uc.flativocolega = true AND (fc.flacesso = var_status OR var_status IS NULL)
	UNION
	SELECT	 uc.idusuario
		,LOWER(u.nomeusuario) AS nome
		,CASE WHEN CAST(fc.flacesso AS INTEGER) IS NULL THEN 0 ELSE CAST(fc.flacesso AS INTEGER) END AS status
		,CASE WHEN uf.idusuariofoto IS NULL THEN NULL ELSE uf.idusuariofoto END AS idusuariofoto
		,CASE WHEN uf.idusuariofoto IS NULL THEN NULL ELSE uf.extensao END AS extensao
		,(SELECT * FROM sem_acentos(LOWER(u.nomeusuario))) AS nome_semacento
		,up.sobremim
		,CASE WHEN fc.flacesso IS NULL OR CAST(fc.flacesso AS INTEGER) = 0 THEN 0
		 ELSE 
		     CASE WHEN sm.flbloquear = TRUE OR fc.flacesso IS NULL OR fc.flacesso = FALSE THEN 0
		     ELSE
		         CASE WHEN fc.flchatativo IS NULL THEN 0
		         ELSE CAST(fc.flchatativo AS INTEGER)
		         END
		     END
	         END AS online
		,CASE WHEN sb.flbloquear IS NULL THEN 0 ELSE CAST(sb.flbloquear AS INTEGER) END AS bloqueado
		,CASE WHEN sb.dataultimamensagem IS NULL THEN CURRENT_TIMESTAMP - INTERVAL '365 DAYS' ELSE sb.dataultimamensagem END
		,d.iddispositivo
		,d.nomedispositivo
	FROM usuariocolega AS uc 
	INNER JOIN usuario AS u ON (u.idusuario = uc.idusuario AND u.flativo = true) 
	LEFT JOIN  usuariofoto AS uf ON (uf.idusuario = u.idusuario)
	LEFT JOIN  feedcontagem AS fc ON (fc.idusuario = u.idusuario)
	LEFT JOIN  dispositivo AS d ON(d.iddispositivo = fc.iddispositivo)
	LEFT JOIN  usuariosobremimperfil AS up ON (up.idusuario = u.idusuario)
	LEFT JOIN  chatmensagensstatus AS sm ON(sm.id_de = var_usuario  AND sm.id_para = u.idusuario)
	LEFT JOIN  chatmensagensstatus AS sb ON(sb.id_de = u.idusuario AND sb.id_para = var_usuario)	
	WHERE uc.idcolega = var_usuario AND uc.flativocolega = true AND (fc.flacesso = var_status OR var_status IS NULL)
	ORDER BY 8 DESC, 10 DESC, 6 ASC
	LOOP
	   RETURN NEXT reg;
	END LOOP; 
END;$_$;


ALTER FUNCTION public.consultar_usuario_chat(idusuario integer, acesso boolean) OWNER TO postgres;

--
-- TOC entry 324 (class 1255 OID 114506)
-- Dependencies: 6 961
-- Name: inserir_feed_espacobaerto(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION inserir_feed_espacobaerto() RETURNS trigger
    LANGUAGE plpgsql
    AS $$DECLARE IdMensagem INTEGER := 0;
DECLARE IdUsRemetente INTEGER :=0;
DECLARE IdUsDestinatario INTEGER :=NULL;
DECLARE IdTabela INTEGER :=0;
DECLARE IdRegTabela INTEGER :=0;
DECLARE IsFeed BOOLEAN :=TRUE;
DECLARE Tipo INTEGER :=NULL;
DECLARE IsAdd BOOLEAN :=FALSE;
BEGIN
   /*--- verifica se tabela possui feed ativo ---*/
   IsFeed := (SELECT status FROM feedtabela WHERE nome = TG_TABLE_NAME AND status = TRUE);  	
	
   IF IsFeed IS TRUE AND TG_OP = 'INSERT' THEN
	IsAdd := TRUE;

	IF TG_TABLE_NAME = 'ambientedeapoiofavorito' THEN
	   IdTabela := 1;

	   /*--- Tipo:1-usuário 2-comunidade ---*/
	   IF EXISTS(SELECT * FROM usuario WHERE idfavorito = NEW.idfavorito) THEN
	      Tipo := 1;
	      IdUsRemetente := (SELECT idusuario FROM usuario WHERE idfavorito = NEW.idfavorito);
	   ELSE
	      Tipo := 2;
	      IdUsRemetente := (SELECT idusuario FROM comunidade WHERE idfavorito = NEW.idfavorito);
	   END IF;
	   
	   IdRegTabela     := NEW.idambientedeapoio;

	   IF Tipo = 1 THEN 
	      IdMensagem := 12;
	   ELSE
	      IdMensagem := 13;
	   END IF;

	END IF;

	IF TG_TABLE_NAME = 'blogcomentario' THEN
	   IdTabela        := 2;
	   IdUsRemetente   := NEW.idusuario;	
	   IdRegTabela     := NEW.idblogcomentario;
	   NEW.datacriacao := CURRENT_TIMESTAMP;
	   
	   /*--- Tipo:1-usuário 2-comunidades ---*/	
	   IF NEW.tipo = 1 THEN
	      IdUsDestinatario := (SELECT idusuario FROM usuarioblog WHERE idusuarioblog = NEW.idblog);
	   ELSE
	      IdUsDestinatario := (SELECT idusuario FROM comunidadeblog WHERE idcomunidadeblog = NEW.idblog);
	   END IF;

	   IF NEW.tipo = 1 THEN	
	      IF IdUsRemetente = IdUsDestinatario THEN   	
	         IdMensagem       := 24;
	         IdUsDestinatario := NULL;
              ELSE
		 IdMensagem := 2;
	      END IF;
	   ELSE
	      IdMensagem := 4;
	   END IF;

	END IF;

	IF TG_TABLE_NAME = 'comunidadealbum' THEN
	   IdTabela        := 3;
	   IdUsRemetente   := (SELECT idusuario FROM comunidade WHERE idcomunidade = NEW.idcomunidade);
	   IdRegTabela     := NEW.idcomunidadealbum;
	   NEW.datacriacao := CURRENT_TIMESTAMP;	
	   IdMensagem      := 15;	
	END IF;

	IF TG_TABLE_NAME = 'comunidadealbumfoto' THEN
	   IdTabela        := 4;
	   IdRegTabela     := (SELECT idcomunidade FROM comunidadealbum WHERE idcomunidadealbum = NEW.idcomunidadealbum);
	   IdUsRemetente   := (SELECT idusuario FROM comunidade WHERE idcomunidade = IdRegTabela);
	   IdRegTabela     := NEW.idcomunidadealbumfoto;
	   NEW.datacriacao := CURRENT_TIMESTAMP;	
	   IdMensagem      := 18;
	END IF;

	IF TG_TABLE_NAME = 'comunidadeblog' THEN
	   IdTabela        := 5;
	   IdUsRemetente   := NEW.idusuario;
	   IdRegTabela     := NEW.idcomunidadeblog;
	   NEW.datacriacao := CURRENT_TIMESTAMP;
	   IdMensagem      := 3;
	END IF;

	IF TG_TABLE_NAME = 'comunidadesugerida' THEN
	   IdTabela         := 6;
	   IdUsRemetente    := NEW.idusuarioconvite;
	   IdUsDestinatario := NEW.idusuario;
	   IdRegTabela      := NEW.idcomunidade;
	   NEW.dataconvite  := CURRENT_TIMESTAMP;	
	   IdMensagem       := 8;
	END IF;

	IF TG_TABLE_NAME = 'conteudodigitalfavorito' THEN
	   IdTabela := 7;

	   /*--- Tipo:1-usuário 2-comunidades ---*/
	   IF EXISTS(SELECT * FROM usuario WHERE idfavorito = NEW.idfavorito) THEN
	      Tipo := 1;
	      IdUsRemetente := (SELECT idusuario FROM usuario WHERE idfavorito = NEW.idfavorito);
	   ELSE
	      Tipo := 2;
	      IdUsRemetente := (SELECT idusuario FROM comunidade WHERE idfavorito = NEW.idfavorito);
	   END IF;

	   IdRegTabela     := NEW.idconteudodigital;

	   IF Tipo = 1 THEN 
	      IdMensagem := 12;
	   ELSE
	      IdMensagem := 13;
	   END IF;
	   
	END IF;

	IF TG_TABLE_NAME = 'enquete' THEN
	   IdTabela        := 8;
	   IdUsRemetente   := NEW.idusuario;
	   IdRegTabela     := NEW.idenquete;
	   IdMensagem      := 17;
	END IF;

	IF TG_TABLE_NAME = 'marcacaoagenda' THEN
	   IdTabela := 9;

	   /*--- Tipo:1-usuário 2-comunidade ---*/	
	   IF NEW.tipo = 1 THEN
	      IdUsRemetente := (SELECT idusuario FROM usuarioagenda WHERE idusuarioagenda = NEW.idagenda);
	   ELSE
	      IdRegTabela   := (SELECT idcomunidade FROM comunidadeagenda WHERE idcomunidadeagenda = NEW.idagenda); 
	      IdUsRemetente := (SELECT idusuario FROM comunidade WHERE idcomunidade = IdRegTabela); 
	   END IF;

	   IdUsDestinatario := NEW.idusuario;
	   IdRegTabela      := NEW.idagenda;
	   NEW.datacriacao  := CURRENT_TIMESTAMP;

	   IF NEW.tipo = 1 THEN
	      IdMensagem := 10;
	   ELSE
	      IdMensagem := 22;
	   END IF;
	   
	END IF;

	IF TG_TABLE_NAME = 'usuarioalbum' THEN
	   IdTabela        := 10;
	   IdUsRemetente   := NEW.idusuario;	
	   IdRegTabela     := NEW.idusuarioalbum;
	   NEW.datacriacao := CURRENT_TIMESTAMP;
	   IdMensagem      := 14;
	END IF;

	IF TG_TABLE_NAME = 'usuarioalbumfoto' THEN
	   IdTabela        := 11;
	   IdUsRemetente   := (SELECT idusuario FROM usuarioalbum WHERE idusuarioalbum = NEW.idusuarioalbum);
	   IdRegTabela     := NEW.idusuarioalbumfoto;
	   NEW.datacriacao := CURRENT_TIMESTAMP;
	   IdMensagem      := 19;
	END IF;

	IF TG_TABLE_NAME = 'usuarioblog' THEN
	   IdTabela        := 12;
	   IdUsRemetente   := NEW.idusuario;
	   IdRegTabela     := NEW.idusuarioblog;
	   NEW.datacriacao := CURRENT_TIMESTAMP;
	   IdMensagem      := 1;
	END IF;

	IF TG_TABLE_NAME = 'usuariocolega' THEN
	   IdTabela         := 13;
	   IdUsRemetente    := NEW.idusuario;
	   IdUsDestinatario := NEW.idcolega; 
	   IdRegTabela      := NEW.idusuariocolega;
	   NEW.datacriacao  := CURRENT_TIMESTAMP;
	   IdMensagem       := 6;
	END IF;

	IF TG_TABLE_NAME = 'usuariorecado' THEN
	   IdTabela         := 14;
	   IdUsRemetente    := NEW.idusuarioautor;
	   IdUsDestinatario := NEW.idusuario;
	   IdRegTabela      := NEW.idusuariorecado;
	   NEW.dataenvio    := CURRENT_TIMESTAMP;
	   IdMensagem       := 5;
	END IF;

	IF TG_TABLE_NAME = 'usuarioagenda' THEN
	   IdTabela         := 15;
	   IdUsRemetente    := NEW.idusuario;
	   IdRegTabela      := NEW.idusuarioagenda;
	   IdMensagem       := 20;
	END IF;

	IF TG_TABLE_NAME = 'comunidadeagenda' THEN
	   IdTabela         := 16;
	   IdUsRemetente    := (SELECT idusuario FROM comunidade WHERE idcomunidade = NEW.idcomunidade);
	   IdRegTabela      := NEW.idcomunidadeagenda;
	   IdMensagem       := 21;
	END IF;

	IF TG_TABLE_NAME = 'comuusuario' THEN
	   IF NEW.flpendente = TRUE THEN
	      IsAdd := FALSE;	
	   ELSE 	
	      IdTabela         := 17;
	      IdUsRemetente    := NEW.idusuario;	
	      IdUsDestinatario := (SELECT idusuario FROM comunidade WHERE idcomunidade = NEW.idcomunidade);
	      IdRegTabela      := NEW.idcomuusuario;
	      IdMensagem       := 9;
	   END IF;
	END IF;

	IF TG_TABLE_NAME = 'comutopico' THEN
	   IdTabela      := 18;
	   IdUsRemetente := NEW.idusuario;
	   IdRegTabela   := NEW.idcomutopico;
	   IdMensagem    := 16;
	END IF;

	IF TG_TABLE_NAME = 'albumcomentario' THEN
	   IdTabela        := 19;
	   IdUsRemetente   := NEW.idusuario;
	   IdRegTabela     := NEW.idalbumcomentario;
	   IdMensagem      := 0;
	   NEW.datacriacao := CURRENT_TIMESTAMP;
	      
	   IF NEW.tipoalbum = 1 THEN
	      IF NEW.tipocomentario = 1 THEN
	          IdUsDestinatario := (SELECT ua.idusuario
					FROM usuarioalbum AS ua
					WHERE ua.idusuarioalbum = NEW.idusuarioalbumfoto);
	      END IF;
	       
	      IF NEW.tipocomentario = 2 THEN
	         IdUsDestinatario := (SELECT ua.idusuario
					FROM usuarioalbumfoto AS af
					INNER JOIN usuarioalbum AS ua
					ON(ua.idusuarioalbum = af.idusuarioalbum)
					WHERE af.idusuarioalbumfoto = NEW.idusuarioalbumfoto);
	      END IF;
	   END IF;

	   IF NEW.tipoalbum = 2 THEN
	      IF NEW.tipocomentario = 1 THEN
	          IdUsDestinatario := (SELECT c.idusuario
					FROM comunidadealbum AS ca
					INNER JOIN comunidade AS c ON (c.idcomunidade = ca.idcomunidade)
					WHERE ca.idcomunidadealbum = NEW.idusuarioalbumfoto);
	      END IF;
	       
	      IF NEW.tipocomentario = 2 THEN
	          IdUsDestinatario := (SELECT c.idusuario
					FROM comunidadealbumfoto AS caf
					INNER JOIN comunidadealbum AS ca ON (ca.idcomunidadealbum = caf.idcomunidadealbum)
					INNER JOIN comunidade AS c ON (c.idcomunidade = ca.idcomunidade)
					WHERE caf.idcomunidadealbumfoto = NEW.idusuarioalbumfoto);
	      END IF;
	   END IF;
	   
	END IF;

	IF TG_TABLE_NAME = 'comutopicomsg' THEN
	   IdTabela         := 20;
	   IdUsRemetente    := NEW.idusuario;
	   IdRegTabela      := NEW.idcomutopicomsg;
	   IdMensagem       := 0;
	   NEW.datacriacao  := CURRENT_TIMESTAMP;
	   IdUsDestinatario := (SELECT c.idusuario FROM comutopicomsg AS ctm
					INNER JOIN comutopico AS ct ON (ct.idcomutopico = ctm.idcomutopico)
					INNER JOIN comunidade AS c ON (c.idcomunidade = ct.idcomunidade)
					WHERE ctm.idcomutopicomsg = NEW.idcomutopicomsg);
	END IF;
	
	IF TG_TABLE_NAME = 'comunidade' THEN
	   IdTabela         := 21;
	   IdUsRemetente    := NEW.idusuario;
	   IdRegTabela      := NEW.idcomunidade;
	   IdMensagem       := 25;
	   NEW.datacriacao  := CURRENT_TIMESTAMP;
	END IF;


   END IF;

   IF IsFeed IS TRUE AND TG_OP = 'UPDATE' THEN
	IF TG_TABLE_NAME = 'marcacaoagenda' THEN

	   IF NEW.aceito = TRUE THEN
	      IsAdd := TRUE;
	      IdTabela := 9;

	      /*--- Tipo:1-usuário 2-comunidade ---*/	
	      IF NEW.tipo = 1 THEN
	         IdUsRemetente := (SELECT idusuario FROM usuarioagenda WHERE idusuarioagenda = NEW.idagenda);
	      ELSE
	         IdRegTabela   := (SELECT idcomunidade FROM comunidadeagenda WHERE idcomunidadeagenda = NEW.idagenda); 
	         IdUsRemetente := (SELECT idusuario FROM comunidade WHERE idcomunidade = IdRegTabela); 
	      END IF;

	      IdUsDestinatario := NEW.idusuario;
	      IdRegTabela      := NEW.idagenda;
	      NEW.datacriacao  := CURRENT_TIMESTAMP;

	      IF NEW.tipo = 1 THEN	
	         IdMensagem := 11;
	      ELSE
		 IdMensagem := 23;
	      END IF;
	   END IF;

	END IF;

	IF TG_TABLE_NAME = 'usuariocolega' THEN
	   IF NEW.flativocolega = TRUE THEN
	      IsAdd            := TRUE;	
	      IdTabela         := 13;
	      IdUsRemetente    := NEW.idusuario;
	      IdUsDestinatario := NEW.idcolega;
	      IdRegTabela      := NEW.idusuariocolega;
	      NEW.datacriacao  := CURRENT_TIMESTAMP;
	      IdMensagem       := 7;
	   END IF;
	END IF;

	IF TG_TABLE_NAME = 'comuusuario' THEN
	   IF NEW.flpendente = FALSE THEN
	      IsAdd            := TRUE;
	      IdTabela         := 17;
	      IdUsRemetente    := NEW.idusuario;
	      IdUsDestinatario := (SELECT idusuario FROM comunidade WHERE idcomunidade = NEW.idcomunidade);
	      IdRegTabela      := NEW.idcomuusuario;
	      IdMensagem       := 9;
	   END IF;
	END IF;
	
   END IF;

   IF IdMensagem = 7 THEN
      IF EXISTS(SELECT * FROM feeddetalhe 
			WHERE idusuarioremetente = IdUsRemetente
			AND   idusuariodestinatario = IdUsDestinatario
			AND   idfeedtabela = IdTabela
			AND   idfeedmensagem = IdMensagem
			AND   idregistrotabela = IdRegTabela) THEN
	 IsAdd = FALSE;
      END IF;
   END IF;
		
   IF IsAdd = TRUE AND IdMensagem > 0 THEN
	INSERT INTO feeddetalhe(
		 idusuarioremetente
		,idusuariodestinatario
		,idfeedtabela
		,idfeedmensagem
		,idregistrotabela
		,datacriacao
	)
	VALUES(
		 IdUsRemetente
		,IdUsDestinatario
		,IdTabela
		,IdMensagem
		,IdRegTabela
		,CURRENT_TIMESTAMP
	);
   END IF;

   IF IsAdd = TRUE AND IdUsDestinatario > 0 AND IdUsRemetente != IdUsDestinatario THEN   		
	/*--- Insere contagem para visualizar as notificações do usuário ---*/
	IF NOT EXISTS(SELECT * FROM feedcontagem WHERE idusuario = IdUsRemetente) THEN
	   INSERT INTO feedcontagem(idusuario) VALUES(IdUsRemetente);
	END IF;
	
	IF NOT EXISTS(SELECT * FROM feedcontagem WHERE idusuario = IdUsDestinatario) THEN
	   INSERT INTO feedcontagem(idusuario) VALUES(IdUsDestinatario);
	END IF;
	   
	/*--- icone de recados ---*/
	IF IdTabela = 14 AND IdMensagem = 5 THEN
	   UPDATE feedcontagem
		SET  qtd_feed_recados = qtd_feed_recados + 1
		    ,datacriacao = CURRENT_TIMESTAMP
		WHERE idusuario = IdUsDestinatario;
	END IF;

	/*--- icone de colegas ---*/
	IF IdTabela = 13 THEN 
	   IF IdMensagem = 6 THEN
	      UPDATE feedcontagem
		SET  qtd_feed_colegas = qtd_feed_colegas + 1
		    ,datacriacao = CURRENT_TIMESTAMP 
		WHERE idusuario = IdUsDestinatario;
	   END IF;
	   
	   IF IdMensagem = 7 THEN			
	      UPDATE feedcontagem
		SET  qtd_feed_colegas = qtd_feed_colegas + 1
		    ,datacriacao = CURRENT_TIMESTAMP 
		WHERE idusuario = IdUsRemetente;
	   END IF;

	END IF;   

	/*--- icone de comunidades ---*/
	IF IdTabela = 6 OR IdTabela = 17 THEN 
	   IF IdMensagem = 8 OR IdMensagem = 9 THEN
	      UPDATE feedcontagem
		SET  qtd_feed_comunidades = qtd_feed_comunidades + 1
		    ,datacriacao = CURRENT_TIMESTAMP
		WHERE idusuario = IdUsDestinatario;
	   END IF;
	END IF;
	
	IF IdTabela = 20 THEN
	   UPDATE feedcontagem
		SET  qtd_feed_comunidades = qtd_feed_comunidades + 1
		    ,datacriacao = CURRENT_TIMESTAMP
		WHERE idusuario = IdUsDestinatario;
	END IF;	

	/*--- icone de albuns ---*/
	IF IdTabela = 19 THEN
	   UPDATE feedcontagem
		SET  qtd_feed_albuns = qtd_feed_albuns + 1
		    ,datacriacao = CURRENT_TIMESTAMP
		WHERE idusuario = IdUsDestinatario;
	END IF;

	/*--- icone de agenda ---*/
	IF IdTabela = 9 THEN
	   IF IdMensagem = 10 THEN
	      UPDATE feedcontagem
		SET  qtd_feed_agenda = qtd_feed_agenda + 1
		    ,datacriacao = CURRENT_TIMESTAMP
		WHERE idusuario = IdUsDestinatario;
	   END IF;
	END IF;
	
	/*--- icone de blog ---*/
	IF IdTabela = 2 THEN
	   UPDATE feedcontagem
		SET  qtd_feed_blog = qtd_feed_blog + 1
		    ,datacriacao = CURRENT_TIMESTAMP
		WHERE idusuario = IdUsDestinatario; 
	END IF;
   END IF;
   
   RETURN NEW;
END$$;


ALTER FUNCTION public.inserir_feed_espacobaerto() OWNER TO postgres;

--
-- TOC entry 322 (class 1255 OID 114508)
-- Dependencies: 6 961
-- Name: inserir_mensagens_chat(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION inserir_mensagens_chat() RETURNS trigger
    LANGUAGE plpgsql
    AS $$BEGIN
   IF TG_OP = 'INSERT' THEN
      IF NEW.id_de = NEW.id_para THEN
	 RAISE EXCEPTION 'Usuário remetente e destinatário devem ser diferentes!';
      END IF;

      IF TRIM(NEW.mensagem) = '' THEN
         RAISE EXCEPTION 'Mensagem não pode ser vazío!';	
      END IF;

      IF NOT EXISTS(
             SELECT * FROM usuariocolega AS uc
			WHERE (uc.idusuario = NEW.id_de OR uc.idcolega = NEW.id_de) AND uc.flativo = true
			AND EXISTS(SELECT * FROM usuariocolega AS vc WHERE (uc.idusuario = NEW.id_para OR uc.idcolega = NEW.id_para) AND vc.flativo = true)) THEN
         RAISE EXCEPTION 'Usuário remetente não é colega!';
      END IF;

      IF NOT EXISTS(
             SELECT * FROM usuariocolega AS uc
			WHERE (uc.idusuario = NEW.id_para OR uc.idcolega = NEW.id_para) AND uc.flativo = true
			AND EXISTS(SELECT * FROM usuariocolega AS vc WHERE (uc.idusuario = NEW.id_de OR uc.idcolega = NEW.id_de) AND vc.flativo = true)) THEN
         RAISE EXCEPTION 'Usuário destinatário não é colega!';
      END IF;

      IF NOT EXISTS(SELECT * FROM feedcontagem AS fc WHERE fc.idusuario = NEW.id_de AND fc.flacesso = TRUE) THEN
         RAISE EXCEPTION 'Usuário não esta logado!';
      END IF;
   END IF;
   
   IF TG_OP = 'UPDATE' THEN
      IF NEW.id_de != OLD.id_de OR NEW.id_para != OLD.id_para OR NEW.mensagem != OLD.mensagem OR NEW.data != OLD.data THEN
         RAISE EXCEPTION 'Registro não pode ser alterado!';
      END IF;
   END IF;
   
   RETURN NEW;
END$$;


ALTER FUNCTION public.inserir_mensagens_chat() OWNER TO postgres;

--
-- TOC entry 326 (class 1255 OID 114509)
-- Dependencies: 6
-- Name: sem_acentos(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION sem_acentos(character varying) RETURNS character varying
    LANGUAGE sql
    AS $_$
SELECT TRANSLATE($1, 'áéíóúàèìòùãõâêîôôäëïöüçÁÉÍÓÚÀÈÌÒÙÃÕÂÊÎÔÛÄËÏÖÜÇ', 'aeiouaeiouaoaeiooaeioucAEIOUAEIOUAOAEIOOAEIOUC')
$_$;


ALTER FUNCTION public.sem_acentos(character varying) OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 165 (class 1259 OID 114510)
-- Dependencies: 2230 6
-- Name: acessibilidade; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE acessibilidade (
    idacessibilidade integer NOT NULL,
    nomeacessibilidade text NOT NULL,
    descricaoacessibilidade text,
    idacessibilidadepai integer,
    datacriacao timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.acessibilidade OWNER TO postgres;

--
-- TOC entry 166 (class 1259 OID 114517)
-- Dependencies: 165 6
-- Name: acessibilidade_idacessibilidade_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE acessibilidade_idacessibilidade_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.acessibilidade_idacessibilidade_seq OWNER TO postgres;

--
-- TOC entry 2941 (class 0 OID 0)
-- Dependencies: 166
-- Name: acessibilidade_idacessibilidade_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE acessibilidade_idacessibilidade_seq OWNED BY acessibilidade.idacessibilidade;


--
-- TOC entry 167 (class 1259 OID 114519)
-- Dependencies: 2232 2233 6
-- Name: agendacomentario; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE agendacomentario (
    idagendacomentario integer NOT NULL,
    idagenda integer NOT NULL,
    mensagem text,
    tipo integer,
    datacriacao timestamp without time zone DEFAULT now() NOT NULL,
    idusuario integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.agendacomentario OWNER TO postgres;

--
-- TOC entry 168 (class 1259 OID 114527)
-- Dependencies: 6 167
-- Name: agendacomentario_idagendacomentario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE agendacomentario_idagendacomentario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.agendacomentario_idagendacomentario_seq OWNER TO postgres;

--
-- TOC entry 2944 (class 0 OID 0)
-- Dependencies: 168
-- Name: agendacomentario_idagendacomentario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE agendacomentario_idagendacomentario_seq OWNED BY agendacomentario.idagendacomentario;


--
-- TOC entry 169 (class 1259 OID 114529)
-- Dependencies: 6
-- Name: album_idalbum_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE album_idalbum_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.album_idalbum_seq OWNER TO postgres;

--
-- TOC entry 170 (class 1259 OID 114531)
-- Dependencies: 2235 2236 2237 6
-- Name: albumcomentario; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE albumcomentario (
    idalbumcomentario integer NOT NULL,
    idusuarioalbumfoto integer NOT NULL,
    mensagem text,
    tipocomentario integer,
    tipoalbum integer,
    datacriacao timestamp without time zone DEFAULT now() NOT NULL,
    idusuario integer DEFAULT 1 NOT NULL,
    visto boolean DEFAULT true
);


ALTER TABLE public.albumcomentario OWNER TO postgres;

--
-- TOC entry 171 (class 1259 OID 114540)
-- Dependencies: 170 6
-- Name: albumcomentario_idalbumcomentario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE albumcomentario_idalbumcomentario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.albumcomentario_idalbumcomentario_seq OWNER TO postgres;

--
-- TOC entry 2948 (class 0 OID 0)
-- Dependencies: 171
-- Name: albumcomentario_idalbumcomentario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE albumcomentario_idalbumcomentario_seq OWNED BY albumcomentario.idalbumcomentario;


--
-- TOC entry 172 (class 1259 OID 114542)
-- Dependencies: 6
-- Name: albumfoto_idalbumfoto_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE albumfoto_idalbumfoto_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.albumfoto_idalbumfoto_seq OWNER TO postgres;

--
-- TOC entry 173 (class 1259 OID 114544)
-- Dependencies: 6
-- Name: ambientedeapoio_idambientedeapoio_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ambientedeapoio_idambientedeapoio_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ambientedeapoio_idambientedeapoio_seq OWNER TO postgres;

--
-- TOC entry 174 (class 1259 OID 114546)
-- Dependencies: 2239 2240 2241 6
-- Name: ambientedeapoio; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE ambientedeapoio (
    idambientedeapoio integer DEFAULT nextval('ambientedeapoio_idambientedeapoio_seq'::regclass) NOT NULL,
    idambientedeapoiocategoria integer NOT NULL,
    titulo character varying(150) NOT NULL,
    url character varying(200),
    urlprojeto character varying(200),
    descricao text,
    usopedagogico text,
    acessos integer DEFAULT 10 NOT NULL,
    fldestaque boolean DEFAULT false NOT NULL,
    idusuariopublicador integer NOT NULL
);


ALTER TABLE public.ambientedeapoio OWNER TO postgres;

--
-- TOC entry 175 (class 1259 OID 114555)
-- Dependencies: 6
-- Name: ambientedeapoiocategoria_idambientedeapoiocategoria_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ambientedeapoiocategoria_idambientedeapoiocategoria_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ambientedeapoiocategoria_idambientedeapoiocategoria_seq OWNER TO postgres;

--
-- TOC entry 176 (class 1259 OID 114557)
-- Dependencies: 2242 6
-- Name: ambientedeapoiocategoria; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE ambientedeapoiocategoria (
    idambientedeapoiocategoria integer DEFAULT nextval('ambientedeapoiocategoria_idambientedeapoiocategoria_seq'::regclass) NOT NULL,
    nomeambientedeapoiocategoria character varying(150) NOT NULL
);


ALTER TABLE public.ambientedeapoiocategoria OWNER TO postgres;

--
-- TOC entry 177 (class 1259 OID 114561)
-- Dependencies: 6
-- Name: ambientedeapoiocomentario_idambientedeapoiocomentario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ambientedeapoiocomentario_idambientedeapoiocomentario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ambientedeapoiocomentario_idambientedeapoiocomentario_seq OWNER TO postgres;

--
-- TOC entry 178 (class 1259 OID 114563)
-- Dependencies: 2243 6
-- Name: ambientedeapoiocomentario; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE ambientedeapoiocomentario (
    idambientedeapoiocomentario integer DEFAULT nextval('ambientedeapoiocomentario_idambientedeapoiocomentario_seq'::regclass) NOT NULL,
    idambientedeapoio integer,
    idusuario integer,
    comentario text,
    datacriacao timestamp with time zone
);


ALTER TABLE public.ambientedeapoiocomentario OWNER TO postgres;

--
-- TOC entry 179 (class 1259 OID 114570)
-- Dependencies: 6
-- Name: ambientedeapoiofavorito; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE ambientedeapoiofavorito (
    idambientedeapoio integer NOT NULL,
    idfavorito integer NOT NULL
);


ALTER TABLE public.ambientedeapoiofavorito OWNER TO postgres;

--
-- TOC entry 180 (class 1259 OID 114573)
-- Dependencies: 6
-- Name: ambientedeapoiotag; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE ambientedeapoiotag (
    idambientedeapoio integer NOT NULL,
    idtag integer NOT NULL
);


ALTER TABLE public.ambientedeapoiotag OWNER TO postgres;

--
-- TOC entry 181 (class 1259 OID 114576)
-- Dependencies: 6
-- Name: ava_idava_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ava_idava_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ava_idava_seq OWNER TO postgres;

--
-- TOC entry 182 (class 1259 OID 114578)
-- Dependencies: 6
-- Name: avacomentario_idavacomentario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE avacomentario_idavacomentario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.avacomentario_idavacomentario_seq OWNER TO postgres;

--
-- TOC entry 183 (class 1259 OID 114580)
-- Dependencies: 6
-- Name: blog_idblog_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE blog_idblog_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.blog_idblog_seq OWNER TO postgres;

--
-- TOC entry 184 (class 1259 OID 114582)
-- Dependencies: 2244 2245 2246 6
-- Name: blogcomentario; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE blogcomentario (
    idblogcomentario integer NOT NULL,
    idblog integer NOT NULL,
    mensagem text,
    tipo integer,
    datacriacao timestamp without time zone DEFAULT now() NOT NULL,
    idusuario integer DEFAULT 1 NOT NULL,
    visto boolean DEFAULT true
);


ALTER TABLE public.blogcomentario OWNER TO postgres;

--
-- TOC entry 185 (class 1259 OID 114591)
-- Dependencies: 6 184
-- Name: blogcomentario_idblogcomentario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE blogcomentario_idblogcomentario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.blogcomentario_idblogcomentario_seq OWNER TO postgres;

--
-- TOC entry 2963 (class 0 OID 0)
-- Dependencies: 185
-- Name: blogcomentario_idblogcomentario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE blogcomentario_idblogcomentario_seq OWNED BY blogcomentario.idblogcomentario;


--
-- TOC entry 186 (class 1259 OID 114593)
-- Dependencies: 6
-- Name: canal; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE canal (
    idcanal integer NOT NULL,
    nomecanal character varying(500) NOT NULL
);


ALTER TABLE public.canal OWNER TO postgres;

--
-- TOC entry 187 (class 1259 OID 114596)
-- Dependencies: 186 6
-- Name: canal_idcanal_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE canal_idcanal_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.canal_idcanal_seq OWNER TO postgres;

--
-- TOC entry 2966 (class 0 OID 0)
-- Dependencies: 187
-- Name: canal_idcanal_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE canal_idcanal_seq OWNED BY canal.idcanal;


--
-- TOC entry 188 (class 1259 OID 114598)
-- Dependencies: 6
-- Name: categoriacomponentecurricular; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE categoriacomponentecurricular (
    idcategoriacomponentecurricular integer NOT NULL,
    nomecategoriacomponentecurricular character varying(250) NOT NULL
);


ALTER TABLE public.categoriacomponentecurricular OWNER TO postgres;

--
-- TOC entry 189 (class 1259 OID 114601)
-- Dependencies: 188 6
-- Name: categoriacomponentecurricular_idcategoriacomponentecurricul_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE categoriacomponentecurricular_idcategoriacomponentecurricul_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.categoriacomponentecurricular_idcategoriacomponentecurricul_seq OWNER TO postgres;

--
-- TOC entry 2969 (class 0 OID 0)
-- Dependencies: 189
-- Name: categoriacomponentecurricular_idcategoriacomponentecurricul_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE categoriacomponentecurricular_idcategoriacomponentecurricul_seq OWNED BY categoriacomponentecurricular.idcategoriacomponentecurricular;


--
-- TOC entry 190 (class 1259 OID 114603)
-- Dependencies: 2250 2251 6
-- Name: chatmensagens; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE chatmensagens (
    id integer NOT NULL,
    id_de integer,
    id_para integer,
    mensagem character varying(500) NOT NULL,
    data timestamp without time zone DEFAULT now() NOT NULL,
    lido boolean DEFAULT false NOT NULL
);


ALTER TABLE public.chatmensagens OWNER TO postgres;

--
-- TOC entry 191 (class 1259 OID 114611)
-- Dependencies: 190 6
-- Name: chatmensagens_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE chatmensagens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.chatmensagens_id_seq OWNER TO postgres;

--
-- TOC entry 2972 (class 0 OID 0)
-- Dependencies: 191
-- Name: chatmensagens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE chatmensagens_id_seq OWNED BY chatmensagens.id;


--
-- TOC entry 192 (class 1259 OID 114613)
-- Dependencies: 2253 2254 2255 6
-- Name: chatmensagensstatus; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE chatmensagensstatus (
    id integer NOT NULL,
    id_de integer NOT NULL,
    id_para integer NOT NULL,
    flavisar boolean DEFAULT false NOT NULL,
    flbloquear boolean DEFAULT false NOT NULL,
    dataultimamensagem timestamp without time zone DEFAULT now()
);


ALTER TABLE public.chatmensagensstatus OWNER TO postgres;

--
-- TOC entry 193 (class 1259 OID 114619)
-- Dependencies: 6 192
-- Name: chatmensagensstatus_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE chatmensagensstatus_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.chatmensagensstatus_id_seq OWNER TO postgres;

--
-- TOC entry 2975 (class 0 OID 0)
-- Dependencies: 193
-- Name: chatmensagensstatus_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE chatmensagensstatus_id_seq OWNED BY chatmensagensstatus.id;


--
-- TOC entry 194 (class 1259 OID 114621)
-- Dependencies: 6
-- Name: componentecurricular_idcomponentecurricular_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE componentecurricular_idcomponentecurricular_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.componentecurricular_idcomponentecurricular_seq OWNER TO postgres;

--
-- TOC entry 195 (class 1259 OID 114623)
-- Dependencies: 2257 6
-- Name: componentecurricular; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE componentecurricular (
    idcomponentecurricular integer DEFAULT nextval('componentecurricular_idcomponentecurricular_seq'::regclass) NOT NULL,
    idnivelensino integer,
    nomecomponentecurricular character varying(150) NOT NULL,
    idcategoriacomponentecurricular integer
);


ALTER TABLE public.componentecurricular OWNER TO postgres;

--
-- TOC entry 196 (class 1259 OID 114627)
-- Dependencies: 2258 2259 2260 6
-- Name: componentecurriculartopico; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE componentecurriculartopico (
    idcomponentecurriculartopico integer NOT NULL,
    idcomponentecurricular integer NOT NULL,
    nomecomponentecurriculartopico character varying(3000) NOT NULL,
    urlcomponentecurriculartopico character varying(500),
    idcomponentecurriculartopicopai integer,
    flvisivel boolean DEFAULT true,
    flativo boolean DEFAULT true NOT NULL,
    datacriacao timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.componentecurriculartopico OWNER TO postgres;

--
-- TOC entry 197 (class 1259 OID 114636)
-- Dependencies: 196 6
-- Name: componentecurriculartopico_idcomponentecurriculartopico_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE componentecurriculartopico_idcomponentecurriculartopico_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.componentecurriculartopico_idcomponentecurriculartopico_seq OWNER TO postgres;

--
-- TOC entry 2980 (class 0 OID 0)
-- Dependencies: 197
-- Name: componentecurriculartopico_idcomponentecurriculartopico_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE componentecurriculartopico_idcomponentecurriculartopico_seq OWNED BY componentecurriculartopico.idcomponentecurriculartopico;


--
-- TOC entry 198 (class 1259 OID 114638)
-- Dependencies: 6
-- Name: comuagenda_idcomunidaderelacionada_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE comuagenda_idcomunidaderelacionada_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.comuagenda_idcomunidaderelacionada_seq OWNER TO postgres;

--
-- TOC entry 199 (class 1259 OID 114640)
-- Dependencies: 6
-- Name: comunidade_idcomunidade_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE comunidade_idcomunidade_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.comunidade_idcomunidade_seq OWNER TO postgres;

--
-- TOC entry 200 (class 1259 OID 114642)
-- Dependencies: 2262 2263 2264 2265 2266 2267 6
-- Name: comunidade; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE comunidade (
    idcomunidade integer DEFAULT nextval('comunidade_idcomunidade_seq'::regclass) NOT NULL,
    idfavorito integer NOT NULL,
    idusuario integer NOT NULL,
    nomecomunidade character varying(150) NOT NULL,
    descricao text,
    datacriacao timestamp without time zone DEFAULT now() NOT NULL,
    qtdvisitas integer,
    flpendente boolean DEFAULT true,
    avaliacao integer DEFAULT 0,
    flmoderausuario boolean DEFAULT false,
    ativa boolean DEFAULT true NOT NULL
);


ALTER TABLE public.comunidade OWNER TO postgres;

--
-- TOC entry 201 (class 1259 OID 114654)
-- Dependencies: 2268 6
-- Name: comunidadeagenda; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE comunidadeagenda (
    idcomunidadeagenda integer NOT NULL,
    idcomunidade integer NOT NULL,
    datainicio timestamp without time zone,
    datafim timestamp without time zone,
    evento character varying(250) NOT NULL,
    mensagem text,
    link1 character varying(200),
    linktitulo1 character varying(60),
    link2 character varying(200),
    linktitulo2 character varying(60),
    link3 character varying(200),
    linktitulo3 character varying(60),
    local text DEFAULT ''::text NOT NULL
);


ALTER TABLE public.comunidadeagenda OWNER TO postgres;

--
-- TOC entry 202 (class 1259 OID 114661)
-- Dependencies: 6 201
-- Name: comunidadeagenda_idcomunidadeagenda_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE comunidadeagenda_idcomunidadeagenda_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.comunidadeagenda_idcomunidadeagenda_seq OWNER TO postgres;

--
-- TOC entry 2986 (class 0 OID 0)
-- Dependencies: 202
-- Name: comunidadeagenda_idcomunidadeagenda_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE comunidadeagenda_idcomunidadeagenda_seq OWNED BY comunidadeagenda.idcomunidadeagenda;


--
-- TOC entry 203 (class 1259 OID 114663)
-- Dependencies: 2270 6
-- Name: comunidadealbum; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE comunidadealbum (
    idcomunidade integer NOT NULL,
    idcomunidadealbum integer NOT NULL,
    datacriacao timestamp without time zone DEFAULT now(),
    titulo character varying(150) NOT NULL
);


ALTER TABLE public.comunidadealbum OWNER TO postgres;

--
-- TOC entry 204 (class 1259 OID 114667)
-- Dependencies: 203 6
-- Name: comunidadealbum_idcomunidadealbum_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE comunidadealbum_idcomunidadealbum_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.comunidadealbum_idcomunidadealbum_seq OWNER TO postgres;

--
-- TOC entry 2989 (class 0 OID 0)
-- Dependencies: 204
-- Name: comunidadealbum_idcomunidadealbum_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE comunidadealbum_idcomunidadealbum_seq OWNED BY comunidadealbum.idcomunidadealbum;


--
-- TOC entry 205 (class 1259 OID 114669)
-- Dependencies: 2272 6
-- Name: comunidadealbumfoto; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE comunidadealbumfoto (
    idcomunidadealbumfoto integer NOT NULL,
    idcomunidadealbum integer NOT NULL,
    legenda character varying(250),
    extensao character varying(10),
    flperfil boolean DEFAULT false,
    datacriacao timestamp without time zone
);


ALTER TABLE public.comunidadealbumfoto OWNER TO postgres;

--
-- TOC entry 206 (class 1259 OID 114673)
-- Dependencies: 205 6
-- Name: comunidadealbumfoto_idcomunidadealbumfoto_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE comunidadealbumfoto_idcomunidadealbumfoto_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.comunidadealbumfoto_idcomunidadealbumfoto_seq OWNER TO postgres;

--
-- TOC entry 2992 (class 0 OID 0)
-- Dependencies: 206
-- Name: comunidadealbumfoto_idcomunidadealbumfoto_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE comunidadealbumfoto_idcomunidadealbumfoto_seq OWNED BY comunidadealbumfoto.idcomunidadealbumfoto;


--
-- TOC entry 207 (class 1259 OID 114675)
-- Dependencies: 2274 6
-- Name: comunidadeblog; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE comunidadeblog (
    idcomunidadeblog integer NOT NULL,
    titulo character varying(250) NOT NULL,
    datacriacao timestamp without time zone DEFAULT now(),
    texto text,
    idcomunidade integer NOT NULL,
    idusuario integer
);


ALTER TABLE public.comunidadeblog OWNER TO postgres;

--
-- TOC entry 208 (class 1259 OID 114682)
-- Dependencies: 6 207
-- Name: comunidadeblog_idcomunidadeblog_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE comunidadeblog_idcomunidadeblog_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.comunidadeblog_idcomunidadeblog_seq OWNER TO postgres;

--
-- TOC entry 2995 (class 0 OID 0)
-- Dependencies: 208
-- Name: comunidadeblog_idcomunidadeblog_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE comunidadeblog_idcomunidadeblog_seq OWNED BY comunidadeblog.idcomunidadeblog;


--
-- TOC entry 209 (class 1259 OID 114684)
-- Dependencies: 6
-- Name: comunidadefoto; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE comunidadefoto (
    idcomunidadefoto integer NOT NULL,
    idcomunidade integer,
    extensao character varying
);


ALTER TABLE public.comunidadefoto OWNER TO postgres;

--
-- TOC entry 210 (class 1259 OID 114690)
-- Dependencies: 6 209
-- Name: comunidadefoto_idcomunidadefoto_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE comunidadefoto_idcomunidadefoto_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.comunidadefoto_idcomunidadefoto_seq OWNER TO postgres;

--
-- TOC entry 2998 (class 0 OID 0)
-- Dependencies: 210
-- Name: comunidadefoto_idcomunidadefoto_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE comunidadefoto_idcomunidadefoto_seq OWNED BY comunidadefoto.idcomunidadefoto;


--
-- TOC entry 211 (class 1259 OID 114692)
-- Dependencies: 2277 6
-- Name: comunidadesugerida; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE comunidadesugerida (
    idusuario integer NOT NULL,
    idcomunidade integer NOT NULL,
    idusuarioconvite integer NOT NULL,
    visto boolean,
    dataconvite timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.comunidadesugerida OWNER TO postgres;

--
-- TOC entry 212 (class 1259 OID 114696)
-- Dependencies: 6
-- Name: comunidadetag; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE comunidadetag (
    idcomunidade integer NOT NULL,
    idtag integer NOT NULL
);


ALTER TABLE public.comunidadetag OWNER TO postgres;

--
-- TOC entry 213 (class 1259 OID 114699)
-- Dependencies: 6
-- Name: comurelacionada_idcomurelacionada_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE comurelacionada_idcomurelacionada_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.comurelacionada_idcomurelacionada_seq OWNER TO postgres;

--
-- TOC entry 214 (class 1259 OID 114701)
-- Dependencies: 2278 6
-- Name: comurelacionada; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE comurelacionada (
    idcomurelacionada integer DEFAULT nextval('comurelacionada_idcomurelacionada_seq'::regclass) NOT NULL,
    idcomunidaderelacionada integer NOT NULL,
    idcomunidade integer NOT NULL
);


ALTER TABLE public.comurelacionada OWNER TO postgres;

--
-- TOC entry 215 (class 1259 OID 114705)
-- Dependencies: 6
-- Name: comutopico_idcomutopico_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE comutopico_idcomutopico_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.comutopico_idcomutopico_seq OWNER TO postgres;

--
-- TOC entry 216 (class 1259 OID 114707)
-- Dependencies: 2279 2280 6
-- Name: comutopico; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE comutopico (
    idcomutopico integer DEFAULT nextval('comutopico_idcomutopico_seq'::regclass) NOT NULL,
    idcomunidade integer NOT NULL,
    idusuario integer NOT NULL,
    titulo character varying(250) NOT NULL,
    datacriacao timestamp without time zone DEFAULT now() NOT NULL,
    mensagem text
);


ALTER TABLE public.comutopico OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 114715)
-- Dependencies: 6
-- Name: comutopicomsg_idcomutopicomsg_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE comutopicomsg_idcomutopicomsg_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.comutopicomsg_idcomutopicomsg_seq OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 114717)
-- Dependencies: 2281 2282 2283 2284 6
-- Name: comutopicomsg; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE comutopicomsg (
    idcomutopicomsg integer DEFAULT nextval('comutopicomsg_idcomutopicomsg_seq'::regclass) NOT NULL,
    idusuario integer NOT NULL,
    mensagem text,
    datacriacao timestamp without time zone DEFAULT now() NOT NULL,
    idcomutopico integer NOT NULL,
    pai integer DEFAULT 0 NOT NULL,
    ativo boolean DEFAULT true NOT NULL
);


ALTER TABLE public.comutopicomsg OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 114727)
-- Dependencies: 2285 2286 6
-- Name: comuusuario; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE comuusuario (
    idcomunidade integer NOT NULL,
    idusuario integer NOT NULL,
    datacriacao timestamp without time zone,
    flmoderador boolean,
    idcomuusuario integer NOT NULL,
    bloqueado boolean DEFAULT false,
    flpendente boolean DEFAULT false
);


ALTER TABLE public.comuusuario OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 114732)
-- Dependencies: 6 219
-- Name: comuusuario_idcomuusuario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE comuusuario_idcomuusuario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.comuusuario_idcomuusuario_seq OWNER TO postgres;

--
-- TOC entry 3009 (class 0 OID 0)
-- Dependencies: 220
-- Name: comuusuario_idcomuusuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE comuusuario_idcomuusuario_seq OWNED BY comuusuario.idcomuusuario;


--
-- TOC entry 221 (class 1259 OID 114734)
-- Dependencies: 6
-- Name: comuvoto_idcomuvoto_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE comuvoto_idcomuvoto_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.comuvoto_idcomuvoto_seq OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 114736)
-- Dependencies: 2288 2289 6
-- Name: comuvoto; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE comuvoto (
    idcomuvoto integer DEFAULT nextval('comuvoto_idcomuvoto_seq'::regclass) NOT NULL,
    idcomunidade integer NOT NULL,
    idusuario integer NOT NULL,
    voto integer NOT NULL,
    datacriacao timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.comuvoto OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 114741)
-- Dependencies: 6
-- Name: conteudodigital_idconteudodigital_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE conteudodigital_idconteudodigital_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.conteudodigital_idconteudodigital_seq OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 114743)
-- Dependencies: 2290 2291 2292 2293 2294 2295 2296 2297 2298 2299 2300 6
-- Name: conteudodigital; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE conteudodigital (
    idconteudodigital integer DEFAULT nextval('conteudodigital_idconteudodigital_seq'::regclass) NOT NULL,
    idusuariopublicador integer NOT NULL,
    idusuarioaprova integer,
    idformato integer,
    titulo character varying(250) NOT NULL,
    autores character varying(250) NOT NULL,
    fonte character varying(250) NOT NULL,
    descricao text,
    acessibilidade text,
    tamanho character varying(50),
    datapublicacao timestamp without time zone DEFAULT now() NOT NULL,
    datacriacao timestamp without time zone,
    flaprovado boolean DEFAULT false,
    qtddownloads integer DEFAULT 0,
    avaliacao integer DEFAULT 0,
    acessos integer DEFAULT 0,
    idformatoguiapedagogico integer,
    licenca text,
    site character varying(250),
    idformatodownload integer,
    idlicencaconteudo integer DEFAULT 1 NOT NULL,
    idcanal integer DEFAULT 0,
    flsitetematico boolean DEFAULT false,
    idservidor integer DEFAULT 1 NOT NULL,
    fldestaque boolean DEFAULT false NOT NULL,
    idconteudodigitalcategoria integer
);


ALTER TABLE public.conteudodigital OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 114760)
-- Dependencies: 2301 2302 2303 6
-- Name: conteudodigitalcategoria; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE conteudodigitalcategoria (
    idconteudodigitalcategoria integer NOT NULL,
    nomeconteudodigitalcategoria character varying(500) NOT NULL,
    descricaoconteudodigitalcategoria text,
    idconteudodigitalcategoriapai integer,
    flativo boolean DEFAULT true NOT NULL,
    datacriacao timestamp without time zone DEFAULT now() NOT NULL,
    idcanal integer NOT NULL,
    fldestaque boolean DEFAULT false NOT NULL
);


ALTER TABLE public.conteudodigitalcategoria OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 114769)
-- Dependencies: 6 225
-- Name: conteudodigitalcategoria_idconteudodigitalcategoria_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE conteudodigitalcategoria_idconteudodigitalcategoria_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.conteudodigitalcategoria_idconteudodigitalcategoria_seq OWNER TO postgres;

--
-- TOC entry 3016 (class 0 OID 0)
-- Dependencies: 226
-- Name: conteudodigitalcategoria_idconteudodigitalcategoria_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE conteudodigitalcategoria_idconteudodigitalcategoria_seq OWNED BY conteudodigitalcategoria.idconteudodigitalcategoria;


--
-- TOC entry 227 (class 1259 OID 114771)
-- Dependencies: 6
-- Name: conteudodigitalcomentario_idconteudodigitalcomentario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE conteudodigitalcomentario_idconteudodigitalcomentario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.conteudodigitalcomentario_idconteudodigitalcomentario_seq OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 114773)
-- Dependencies: 2305 2306 6
-- Name: conteudodigitalcomentario; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE conteudodigitalcomentario (
    idconteudodigitalcomentario integer DEFAULT nextval('conteudodigitalcomentario_idconteudodigitalcomentario_seq'::regclass) NOT NULL,
    idconteudodigital integer NOT NULL,
    idusuario integer NOT NULL,
    comentario text NOT NULL,
    datacriacao timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.conteudodigitalcomentario OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 114781)
-- Dependencies: 6
-- Name: conteudodigitalcomponente; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE conteudodigitalcomponente (
    idconteudodigital integer NOT NULL,
    idcomponentecurricular integer NOT NULL
);


ALTER TABLE public.conteudodigitalcomponente OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 114784)
-- Dependencies: 6
-- Name: conteudodigitalfavorito; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE conteudodigitalfavorito (
    idconteudodigital integer NOT NULL,
    idfavorito integer NOT NULL
);


ALTER TABLE public.conteudodigitalfavorito OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 114787)
-- Dependencies: 6
-- Name: conteudodigitalrelacionado; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE conteudodigitalrelacionado (
    idconteudodigital integer NOT NULL,
    idconteudodigitalrelacionado integer NOT NULL
);


ALTER TABLE public.conteudodigitalrelacionado OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 114790)
-- Dependencies: 6
-- Name: conteudodigitaltag; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE conteudodigitaltag (
    idconteudodigital integer NOT NULL,
    idtag integer NOT NULL
);


ALTER TABLE public.conteudodigitaltag OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 114793)
-- Dependencies: 6
-- Name: conteudodigitalvoto_idconteudodigitalvoto_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE conteudodigitalvoto_idconteudodigitalvoto_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.conteudodigitalvoto_idconteudodigitalvoto_seq OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 114795)
-- Dependencies: 2307 2308 6
-- Name: conteudodigitalvoto; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE conteudodigitalvoto (
    idconteudodigitalvoto integer DEFAULT nextval('conteudodigitalvoto_idconteudodigitalvoto_seq'::regclass) NOT NULL,
    idconteudodigital integer NOT NULL,
    idusuario integer NOT NULL,
    voto integer NOT NULL,
    datacriacao timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.conteudodigitalvoto OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 114800)
-- Dependencies: 6
-- Name: conteudolicenca; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE conteudolicenca (
    idconteudolicenca integer NOT NULL,
    nomeconteudolicenca text,
    descricaoconteudolicenca text,
    idconteudolicencapai integer,
    siteconteudolicenca character varying(1000)
);


ALTER TABLE public.conteudolicenca OWNER TO postgres;

--
-- TOC entry 236 (class 1259 OID 114806)
-- Dependencies: 235 6
-- Name: conteudolicenca_idconteudolicenca_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE conteudolicenca_idconteudolicenca_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.conteudolicenca_idconteudolicenca_seq OWNER TO postgres;

--
-- TOC entry 3027 (class 0 OID 0)
-- Dependencies: 236
-- Name: conteudolicenca_idconteudolicenca_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE conteudolicenca_idconteudolicenca_seq OWNED BY conteudolicenca.idconteudolicenca;


--
-- TOC entry 237 (class 1259 OID 114808)
-- Dependencies: 6
-- Name: conteudotipo_idconteudotipo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE conteudotipo_idconteudotipo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.conteudotipo_idconteudotipo_seq OWNER TO postgres;

--
-- TOC entry 238 (class 1259 OID 114810)
-- Dependencies: 2310 6
-- Name: conteudotipo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE conteudotipo (
    idconteudotipo integer DEFAULT nextval('conteudotipo_idconteudotipo_seq'::regclass) NOT NULL,
    nomeconteudotipo character varying(150) NOT NULL
);


ALTER TABLE public.conteudotipo OWNER TO postgres;

--
-- TOC entry 239 (class 1259 OID 114814)
-- Dependencies: 6
-- Name: denuncia_iddenuncia_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE denuncia_iddenuncia_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.denuncia_iddenuncia_seq OWNER TO postgres;

--
-- TOC entry 240 (class 1259 OID 114816)
-- Dependencies: 2311 2312 6
-- Name: denuncia; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE denuncia (
    iddenuncia integer DEFAULT nextval('denuncia_iddenuncia_seq'::regclass) NOT NULL,
    idusuario integer NOT NULL,
    url character varying(200),
    mensagem text NOT NULL,
    datacriacao timestamp with time zone,
    titulo character varying(150),
    flvisualizada boolean DEFAULT false
);


ALTER TABLE public.denuncia OWNER TO postgres;

--
-- TOC entry 241 (class 1259 OID 114824)
-- Dependencies: 2313 6
-- Name: dispositivo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE dispositivo (
    iddispositivo integer NOT NULL,
    nomedispositivo character varying(200) NOT NULL,
    datacriacao timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.dispositivo OWNER TO postgres;

--
-- TOC entry 242 (class 1259 OID 114828)
-- Dependencies: 241 6
-- Name: dispositivo_iddispositivo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE dispositivo_iddispositivo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.dispositivo_iddispositivo_seq OWNER TO postgres;

--
-- TOC entry 3034 (class 0 OID 0)
-- Dependencies: 242
-- Name: dispositivo_iddispositivo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE dispositivo_iddispositivo_seq OWNED BY dispositivo.iddispositivo;


--
-- TOC entry 243 (class 1259 OID 114830)
-- Dependencies: 6
-- Name: enquete_idenquete_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE enquete_idenquete_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.enquete_idenquete_seq OWNER TO postgres;

--
-- TOC entry 244 (class 1259 OID 114832)
-- Dependencies: 2315 2316 6
-- Name: enquete; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE enquete (
    idenquete integer DEFAULT nextval('enquete_idenquete_seq'::regclass) NOT NULL,
    idcomunidade integer NOT NULL,
    idusuario integer NOT NULL,
    pergunta character varying(250) NOT NULL,
    datainicio timestamp without time zone DEFAULT now(),
    datafim timestamp without time zone
);


ALTER TABLE public.enquete OWNER TO postgres;

--
-- TOC entry 245 (class 1259 OID 114837)
-- Dependencies: 6
-- Name: enqueteopcao_idenqueteopcao_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE enqueteopcao_idenqueteopcao_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.enqueteopcao_idenqueteopcao_seq OWNER TO postgres;

--
-- TOC entry 246 (class 1259 OID 114839)
-- Dependencies: 2317 6
-- Name: enqueteopcao; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE enqueteopcao (
    idenqueteopcao integer DEFAULT nextval('enqueteopcao_idenqueteopcao_seq'::regclass) NOT NULL,
    idenquete integer NOT NULL,
    opcao character varying(250) NOT NULL
);


ALTER TABLE public.enqueteopcao OWNER TO postgres;

--
-- TOC entry 247 (class 1259 OID 114843)
-- Dependencies: 6
-- Name: enqueteopcaoresposta_idenqueteopcaoresposta_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE enqueteopcaoresposta_idenqueteopcaoresposta_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.enqueteopcaoresposta_idenqueteopcaoresposta_seq OWNER TO postgres;

--
-- TOC entry 248 (class 1259 OID 114845)
-- Dependencies: 2318 6
-- Name: enqueteopcaoresposta; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE enqueteopcaoresposta (
    idenqueteopcaoresposta integer DEFAULT nextval('enqueteopcaoresposta_idenqueteopcaoresposta_seq'::regclass) NOT NULL,
    idenqueteopcao integer NOT NULL,
    idusuario integer NOT NULL,
    idenquete integer NOT NULL
);


ALTER TABLE public.enqueteopcaoresposta OWNER TO postgres;

--
-- TOC entry 249 (class 1259 OID 114849)
-- Dependencies: 6
-- Name: escola_idescola_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE escola_idescola_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.escola_idescola_seq OWNER TO postgres;

--
-- TOC entry 250 (class 1259 OID 114851)
-- Dependencies: 2319 6
-- Name: escola; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE escola (
    idescola bigint DEFAULT nextval('escola_idescola_seq'::regclass) NOT NULL,
    idmunicipio integer NOT NULL,
    nomeescola character varying(150) NOT NULL,
    codigomec character varying(8) NOT NULL
);


ALTER TABLE public.escola OWNER TO postgres;

--
-- TOC entry 251 (class 1259 OID 114855)
-- Dependencies: 6
-- Name: estado_idestado_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE estado_idestado_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.estado_idestado_seq OWNER TO postgres;

--
-- TOC entry 252 (class 1259 OID 114857)
-- Dependencies: 2320 6
-- Name: estado; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE estado (
    idestado bigint DEFAULT nextval('estado_idestado_seq'::regclass) NOT NULL,
    nomeestado character varying(150) NOT NULL,
    codigoibgesiig character varying(16) NOT NULL
);


ALTER TABLE public.estado OWNER TO postgres;

--
-- TOC entry 253 (class 1259 OID 114861)
-- Dependencies: 6
-- Name: favorito_idfavorito_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE favorito_idfavorito_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.favorito_idfavorito_seq OWNER TO postgres;

--
-- TOC entry 254 (class 1259 OID 114863)
-- Dependencies: 2321 6
-- Name: favorito; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE favorito (
    idfavorito integer DEFAULT nextval('favorito_idfavorito_seq'::regclass) NOT NULL
);


ALTER TABLE public.favorito OWNER TO postgres;

--
-- TOC entry 255 (class 1259 OID 114867)
-- Dependencies: 2322 2323 2324 2325 2326 2327 2328 2329 2330 2331 6
-- Name: feedcontagem; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE feedcontagem (
    idfeedcontagem integer NOT NULL,
    idusuario integer NOT NULL,
    qtd_feed_recados integer DEFAULT 0 NOT NULL,
    qtd_feed_colegas integer DEFAULT 0 NOT NULL,
    qtd_feed_comunidades integer DEFAULT 0 NOT NULL,
    qtd_feed_albuns integer DEFAULT 0 NOT NULL,
    qtd_feed_agenda integer DEFAULT 0 NOT NULL,
    qtd_feed_blog integer DEFAULT 0 NOT NULL,
    datacriacao timestamp without time zone DEFAULT now() NOT NULL,
    flacesso boolean DEFAULT false,
    dataacesso timestamp without time zone DEFAULT now(),
    flchatativo boolean DEFAULT false,
    iddispositivo integer
);


ALTER TABLE public.feedcontagem OWNER TO postgres;

--
-- TOC entry 256 (class 1259 OID 114880)
-- Dependencies: 255 6
-- Name: feedcontagem_idfeedcontagem_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE feedcontagem_idfeedcontagem_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.feedcontagem_idfeedcontagem_seq OWNER TO postgres;

--
-- TOC entry 3049 (class 0 OID 0)
-- Dependencies: 256
-- Name: feedcontagem_idfeedcontagem_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE feedcontagem_idfeedcontagem_seq OWNED BY feedcontagem.idfeedcontagem;


--
-- TOC entry 257 (class 1259 OID 114882)
-- Dependencies: 2333 6
-- Name: feeddetalhe; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE feeddetalhe (
    id integer NOT NULL,
    idusuarioremetente integer NOT NULL,
    idusuariodestinatario integer,
    idfeedtabela integer NOT NULL,
    idfeedmensagem integer NOT NULL,
    idregistrotabela integer NOT NULL,
    valorantigo character varying(150),
    valornovo character varying(150),
    datacriacao timestamp without time zone DEFAULT now(),
    idcomunidade integer
);


ALTER TABLE public.feeddetalhe OWNER TO postgres;

--
-- TOC entry 258 (class 1259 OID 114886)
-- Dependencies: 6 257
-- Name: feeddetalhe_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE feeddetalhe_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.feeddetalhe_id_seq OWNER TO postgres;

--
-- TOC entry 3052 (class 0 OID 0)
-- Dependencies: 258
-- Name: feeddetalhe_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE feeddetalhe_id_seq OWNED BY feeddetalhe.id;


--
-- TOC entry 259 (class 1259 OID 114888)
-- Dependencies: 2335 6
-- Name: feedtabela; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE feedtabela (
    id integer NOT NULL,
    nome character varying(100),
    status boolean DEFAULT true
);


ALTER TABLE public.feedtabela OWNER TO postgres;

--
-- TOC entry 260 (class 1259 OID 114892)
-- Dependencies: 6 259
-- Name: feedtabela_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE feedtabela_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.feedtabela_id_seq OWNER TO postgres;

--
-- TOC entry 3055 (class 0 OID 0)
-- Dependencies: 260
-- Name: feedtabela_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE feedtabela_id_seq OWNED BY feedtabela.id;


--
-- TOC entry 261 (class 1259 OID 114894)
-- Dependencies: 6
-- Name: feedtipo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE feedtipo (
    id integer NOT NULL,
    nomefeedtipo character varying(100) NOT NULL
);


ALTER TABLE public.feedtipo OWNER TO postgres;

--
-- TOC entry 262 (class 1259 OID 114897)
-- Dependencies: 261 6
-- Name: feedtipo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE feedtipo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.feedtipo_id_seq OWNER TO postgres;

--
-- TOC entry 3058 (class 0 OID 0)
-- Dependencies: 262
-- Name: feedtipo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE feedtipo_id_seq OWNED BY feedtipo.id;


--
-- TOC entry 263 (class 1259 OID 114899)
-- Dependencies: 6
-- Name: formato_idformato_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE formato_idformato_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.formato_idformato_seq OWNER TO postgres;

--
-- TOC entry 264 (class 1259 OID 114901)
-- Dependencies: 2338 6
-- Name: formato; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE formato (
    idformato integer DEFAULT nextval('formato_idformato_seq'::regclass) NOT NULL,
    nomeformato character varying(150) NOT NULL,
    idconteudotipo integer
);


ALTER TABLE public.formato OWNER TO postgres;

--
-- TOC entry 265 (class 1259 OID 114905)
-- Dependencies: 2339 6
-- Name: marcacaoagenda; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE marcacaoagenda (
    idagenda integer NOT NULL,
    idusuario integer NOT NULL,
    tipo integer NOT NULL,
    visto boolean,
    aceito boolean,
    datacriacao timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.marcacaoagenda OWNER TO postgres;

--
-- TOC entry 266 (class 1259 OID 114909)
-- Dependencies: 6
-- Name: municipio_idmunicipio_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE municipio_idmunicipio_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.municipio_idmunicipio_seq OWNER TO postgres;

--
-- TOC entry 267 (class 1259 OID 114911)
-- Dependencies: 2340 6
-- Name: municipio; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE municipio (
    idmunicipio bigint DEFAULT nextval('municipio_idmunicipio_seq'::regclass) NOT NULL,
    idestado bigint NOT NULL,
    nomemunicipio character varying(150) NOT NULL,
    codigoibgesiig character varying(16) NOT NULL
);


ALTER TABLE public.municipio OWNER TO postgres;

--
-- TOC entry 268 (class 1259 OID 114915)
-- Dependencies: 6
-- Name: nivelensino_idnivelensino_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE nivelensino_idnivelensino_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.nivelensino_idnivelensino_seq OWNER TO postgres;

--
-- TOC entry 269 (class 1259 OID 114917)
-- Dependencies: 2341 6
-- Name: nivelensino; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE nivelensino (
    idnivelensino integer DEFAULT nextval('nivelensino_idnivelensino_seq'::regclass) NOT NULL,
    nomenivelensino character varying(150) NOT NULL
);


ALTER TABLE public.nivelensino OWNER TO postgres;

--
-- TOC entry 270 (class 1259 OID 114921)
-- Dependencies: 2783 6
-- Name: numerocomponentes; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW numerocomponentes AS
    SELECT cdc.idconteudodigital, count(DISTINCT cc.nomecomponentecurricular) AS num FROM conteudodigitalcomponente cdc, componentecurricular cc WHERE (cdc.idcomponentecurricular = cc.idcomponentecurricular) GROUP BY cdc.idconteudodigital;


ALTER TABLE public.numerocomponentes OWNER TO postgres;

--
-- TOC entry 271 (class 1259 OID 114925)
-- Dependencies: 6
-- Name: redesocial; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE redesocial (
    idredesocial integer NOT NULL,
    site character varying(250) NOT NULL,
    rede character varying(250) NOT NULL
);


ALTER TABLE public.redesocial OWNER TO postgres;

--
-- TOC entry 272 (class 1259 OID 114931)
-- Dependencies: 271 6
-- Name: redesocial_idredesocial_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE redesocial_idredesocial_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.redesocial_idredesocial_seq OWNER TO postgres;

--
-- TOC entry 3069 (class 0 OID 0)
-- Dependencies: 272
-- Name: redesocial_idredesocial_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE redesocial_idredesocial_seq OWNED BY redesocial.idredesocial;


--
-- TOC entry 273 (class 1259 OID 114933)
-- Dependencies: 6
-- Name: serie_idserie_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE serie_idserie_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.serie_idserie_seq OWNER TO postgres;

--
-- TOC entry 274 (class 1259 OID 114935)
-- Dependencies: 2343 6
-- Name: serie; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE serie (
    idserie bigint DEFAULT nextval('serie_idserie_seq'::regclass) NOT NULL,
    nomeserie character varying(150) NOT NULL
);


ALTER TABLE public.serie OWNER TO postgres;

--
-- TOC entry 275 (class 1259 OID 114939)
-- Dependencies: 6
-- Name: servidor; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE servidor (
    idservidor integer NOT NULL,
    pathservidor character varying(250) NOT NULL
);


ALTER TABLE public.servidor OWNER TO postgres;

--
-- TOC entry 276 (class 1259 OID 114942)
-- Dependencies: 6 275
-- Name: servidor_idservidor_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE servidor_idservidor_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.servidor_idservidor_seq OWNER TO postgres;

--
-- TOC entry 3074 (class 0 OID 0)
-- Dependencies: 276
-- Name: servidor_idservidor_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE servidor_idservidor_seq OWNED BY servidor.idservidor;


--
-- TOC entry 277 (class 1259 OID 114944)
-- Dependencies: 6
-- Name: tag_idtag_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tag_idtag_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tag_idtag_seq OWNER TO postgres;

--
-- TOC entry 278 (class 1259 OID 114946)
-- Dependencies: 2345 6
-- Name: tag; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tag (
    idtag integer DEFAULT nextval('tag_idtag_seq'::regclass) NOT NULL,
    nometag character varying(150) NOT NULL,
    busca integer,
    dataatualizacao timestamp without time zone
);


ALTER TABLE public.tag OWNER TO postgres;

--
-- TOC entry 279 (class 1259 OID 114950)
-- Dependencies: 6
-- Name: usuario_idusuario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE usuario_idusuario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuario_idusuario_seq OWNER TO postgres;

--
-- TOC entry 280 (class 1259 OID 114952)
-- Dependencies: 2346 2347 2348 2349 6
-- Name: usuario; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuario (
    idusuario integer DEFAULT nextval('usuario_idusuario_seq'::regclass) NOT NULL,
    idfavorito integer NOT NULL,
    idusuariotipo integer NOT NULL,
    idmunicipio integer,
    idescola integer,
    idserie integer,
    categoria character varying(1) DEFAULT 'a'::character varying NOT NULL,
    username character varying(100) NOT NULL,
    matricula bigint,
    nomeusuario character varying(150) NOT NULL,
    datanascimento timestamp without time zone,
    sexo character varying(10),
    telefone character varying(15),
    endereco character varying(250),
    numero character varying(15),
    complemento character varying(100),
    bairro character varying(100),
    cep character varying(9),
    cpf character varying(15),
    rg character varying(20),
    email character varying(150) NOT NULL,
    datacriacao timestamp without time zone DEFAULT now(),
    flativo boolean DEFAULT true,
    dataatualizacao timestamp without time zone,
    senha character varying(100),
    emailpessoal character varying(150)
);


ALTER TABLE public.usuario OWNER TO postgres;

--
-- TOC entry 281 (class 1259 OID 114962)
-- Dependencies: 6
-- Name: usuarioagenda_idusuarioagenda_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE usuarioagenda_idusuarioagenda_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuarioagenda_idusuarioagenda_seq OWNER TO postgres;

--
-- TOC entry 282 (class 1259 OID 114964)
-- Dependencies: 2350 2351 6
-- Name: usuarioagenda; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuarioagenda (
    idusuarioagenda integer DEFAULT nextval('usuarioagenda_idusuarioagenda_seq'::regclass) NOT NULL,
    idusuario integer NOT NULL,
    datainicio timestamp without time zone NOT NULL,
    datafim timestamp without time zone NOT NULL,
    evento character varying(250),
    link1 character varying(200),
    linktitulo1 character varying(60),
    link2 character varying(200),
    linktitulo2 character varying(60),
    link3 character varying(200),
    linktitulo3 character varying(60),
    mensagem text,
    local text DEFAULT ''::text NOT NULL,
    marcacao boolean
);


ALTER TABLE public.usuarioagenda OWNER TO postgres;

--
-- TOC entry 283 (class 1259 OID 114972)
-- Dependencies: 2352 6
-- Name: usuarioalbum; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuarioalbum (
    idusuario integer NOT NULL,
    datacriacao timestamp without time zone DEFAULT now(),
    idusuarioalbum integer NOT NULL,
    titulo character varying(150) NOT NULL
);


ALTER TABLE public.usuarioalbum OWNER TO postgres;

--
-- TOC entry 284 (class 1259 OID 114976)
-- Dependencies: 283 6
-- Name: usuarioalbum_idusuarioalbum_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE usuarioalbum_idusuarioalbum_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuarioalbum_idusuarioalbum_seq OWNER TO postgres;

--
-- TOC entry 3083 (class 0 OID 0)
-- Dependencies: 284
-- Name: usuarioalbum_idusuarioalbum_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE usuarioalbum_idusuarioalbum_seq OWNED BY usuarioalbum.idusuarioalbum;


--
-- TOC entry 285 (class 1259 OID 114978)
-- Dependencies: 2354 6
-- Name: usuarioalbumfoto; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuarioalbumfoto (
    idusuarioalbumfoto integer NOT NULL,
    idusuarioalbum integer NOT NULL,
    legenda character varying(250),
    extensao character varying(10),
    flperfil boolean DEFAULT false,
    datacriacao timestamp without time zone
);


ALTER TABLE public.usuarioalbumfoto OWNER TO postgres;

--
-- TOC entry 286 (class 1259 OID 114982)
-- Dependencies: 6 285
-- Name: usuarioalbumfoto_idusuarioalbumfoto_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE usuarioalbumfoto_idusuarioalbumfoto_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuarioalbumfoto_idusuarioalbumfoto_seq OWNER TO postgres;

--
-- TOC entry 3086 (class 0 OID 0)
-- Dependencies: 286
-- Name: usuarioalbumfoto_idusuarioalbumfoto_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE usuarioalbumfoto_idusuarioalbumfoto_seq OWNED BY usuarioalbumfoto.idusuarioalbumfoto;


--
-- TOC entry 287 (class 1259 OID 114984)
-- Dependencies: 6
-- Name: usuarioamigo_idusuarioamigo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE usuarioamigo_idusuarioamigo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuarioamigo_idusuarioamigo_seq OWNER TO postgres;

--
-- TOC entry 288 (class 1259 OID 114986)
-- Dependencies: 2356 2357 2358 2359 6
-- Name: usuarioamigo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuarioamigo (
    idusuarioamigo integer DEFAULT nextval('usuarioamigo_idusuarioamigo_seq'::regclass) NOT NULL,
    idusuario integer NOT NULL,
    idusuarioindicou integer NOT NULL,
    idusuarioaprovar integer,
    flaprovador boolean DEFAULT false,
    flespacoaberto boolean DEFAULT false,
    datacriacao timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.usuarioamigo OWNER TO postgres;

--
-- TOC entry 289 (class 1259 OID 115002)
-- Dependencies: 2360 2361 2362 6
-- Name: usuariocolega; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuariocolega (
    idusuario integer NOT NULL,
    idusuariocolega integer NOT NULL,
    flativocolega boolean DEFAULT false,
    datacriacao timestamp without time zone DEFAULT now() NOT NULL,
    idcolega integer NOT NULL,
    visto boolean DEFAULT true
);


ALTER TABLE public.usuariocolega OWNER TO postgres;

--
-- TOC entry 290 (class 1259 OID 115008)
-- Dependencies: 6 289
-- Name: usuariocolega_idusuariocolega_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE usuariocolega_idusuariocolega_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuariocolega_idusuariocolega_seq OWNER TO postgres;

--
-- TOC entry 3091 (class 0 OID 0)
-- Dependencies: 290
-- Name: usuariocolega_idusuariocolega_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE usuariocolega_idusuariocolega_seq OWNED BY usuariocolega.idusuariocolega;


--
-- TOC entry 291 (class 1259 OID 115010)
-- Dependencies: 6
-- Name: usuariocomponente; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuariocomponente (
    idusuario integer NOT NULL,
    idcomponentecurricular integer NOT NULL
);


ALTER TABLE public.usuariocomponente OWNER TO postgres;

--
-- TOC entry 292 (class 1259 OID 115013)
-- Dependencies: 6
-- Name: usuariofoto; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuariofoto (
    idusuariofoto integer NOT NULL,
    extensao character varying(5),
    idusuario integer
);


ALTER TABLE public.usuariofoto OWNER TO postgres;

--
-- TOC entry 293 (class 1259 OID 115016)
-- Dependencies: 292 6
-- Name: usuariofoto_idusuariofoto_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE usuariofoto_idusuariofoto_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuariofoto_idusuariofoto_seq OWNER TO postgres;

--
-- TOC entry 3095 (class 0 OID 0)
-- Dependencies: 293
-- Name: usuariofoto_idusuariofoto_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE usuariofoto_idusuariofoto_seq OWNED BY usuariofoto.idusuariofoto;


--
-- TOC entry 294 (class 1259 OID 115018)
-- Dependencies: 6
-- Name: usuariorecado_idusuariorecado_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE usuariorecado_idusuariorecado_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuariorecado_idusuariorecado_seq OWNER TO postgres;

--
-- TOC entry 295 (class 1259 OID 115020)
-- Dependencies: 2364 2365 2366 2367 6
-- Name: usuariorecado; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuariorecado (
    idusuariorecado integer DEFAULT nextval('usuariorecado_idusuariorecado_seq'::regclass) NOT NULL,
    idusuario integer NOT NULL,
    idusuarioautor integer NOT NULL,
    recado text NOT NULL,
    dataenvio timestamp without time zone NOT NULL,
    tiporecado integer DEFAULT 1,
    visto boolean DEFAULT true,
    idrecadorelacionado integer,
    CONSTRAINT usuariorecado_tiporecado_check CHECK (((tiporecado > 0) AND (tiporecado < 4)))
);


ALTER TABLE public.usuariorecado OWNER TO postgres;

--
-- TOC entry 296 (class 1259 OID 115030)
-- Dependencies: 6
-- Name: usuarioredesocial; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuarioredesocial (
    idusuarioredesocial integer NOT NULL,
    idusuario integer NOT NULL,
    idredesocial integer NOT NULL,
    url character varying(250) NOT NULL
);


ALTER TABLE public.usuarioredesocial OWNER TO postgres;

--
-- TOC entry 297 (class 1259 OID 115033)
-- Dependencies: 6 296
-- Name: usuarioredesocial_idusuarioredesocial_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE usuarioredesocial_idusuarioredesocial_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuarioredesocial_idusuarioredesocial_seq OWNER TO postgres;

--
-- TOC entry 3100 (class 0 OID 0)
-- Dependencies: 297
-- Name: usuarioredesocial_idusuarioredesocial_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE usuarioredesocial_idusuarioredesocial_seq OWNED BY usuarioredesocial.idusuarioredesocial;


--
-- TOC entry 298 (class 1259 OID 115035)
-- Dependencies: 6
-- Name: usuariosobremimperfil; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuariosobremimperfil (
    idusuario integer NOT NULL,
    sobremim text NOT NULL,
    cidadenatal character varying(250),
    lattes character varying(250),
    dataenvio timestamp without time zone NOT NULL
);


ALTER TABLE public.usuariosobremimperfil OWNER TO postgres;

--
-- TOC entry 299 (class 1259 OID 115041)
-- Dependencies: 6
-- Name: usuariotag; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuariotag (
    idusuario integer NOT NULL,
    idtag integer NOT NULL
);


ALTER TABLE public.usuariotag OWNER TO postgres;

--
-- TOC entry 300 (class 1259 OID 115044)
-- Dependencies: 6
-- Name: usuariotipo_idusuariotipo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE usuariotipo_idusuariotipo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuariotipo_idusuariotipo_seq OWNER TO postgres;

--
-- TOC entry 301 (class 1259 OID 115046)
-- Dependencies: 2369 6
-- Name: usuariotipo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuariotipo (
    idusuariotipo integer DEFAULT nextval('usuariotipo_idusuariotipo_seq'::regclass) NOT NULL,
    nomeusuariotipo character varying(150) NOT NULL,
    descricao text
);


ALTER TABLE public.usuariotipo OWNER TO postgres;

--
-- TOC entry 302 (class 1259 OID 115053)
-- Dependencies: 2784 6
-- Name: vw_conteudos; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW vw_conteudos AS
    SELECT cd.idconteudodigital, cd.idusuariopublicador, cd.idusuarioaprova, cd.idformato, cd.titulo, cd.autores, cd.fonte, cd.descricao, cd.acessibilidade, cd.tamanho, cd.datapublicacao, cd.datacriacao, cd.flaprovado, cd.qtddownloads, cd.avaliacao, cd.acessos, cd.idformatoguiapedagogico, cd.licenca, cd.site, cd.idformatodownload, cd.idlicencaconteudo, cd.idcanal, cd.flsitetematico, ne.idnivelensino, ne.nomenivelensino AS nivelensino, cc.idcomponentecurricular, cc.nomecomponentecurricular AS componentecurricular FROM (((conteudodigital cd JOIN conteudodigitalcomponente cdc ON ((cd.idconteudodigital = cdc.idconteudodigital))) JOIN componentecurricular cc ON ((cc.idcomponentecurricular = cdc.idcomponentecurricular))) JOIN nivelensino ne ON ((cc.idnivelensino = ne.idnivelensino)));


ALTER TABLE public.vw_conteudos OWNER TO postgres;

--
-- TOC entry 303 (class 1259 OID 115058)
-- Dependencies: 2785 6
-- Name: vw_sitestematicos; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW vw_sitestematicos AS
    SELECT cd.idconteudodigital AS id, cd.titulo, cd.descricao, cd.fonte, ct.nomeconteudotipo AS tipo, cd.site AS link, cd.datapublicacao, f.nomeformato AS formato, cc.idcomponentecurricular, fdownload.nomeformato AS formato_download, fguia.nomeformato AS formato_guia FROM ((((((conteudodigital cd JOIN conteudodigitalcomponente cdc ON ((cd.idconteudodigital = cdc.idconteudodigital))) JOIN componentecurricular cc ON ((cdc.idcomponentecurricular = cc.idcomponentecurricular))) LEFT JOIN formato f ON ((cd.idformato = f.idformato))) LEFT JOIN formato fdownload ON ((cd.idformatodownload = fdownload.idformato))) LEFT JOIN formato fguia ON ((cd.idformatoguiapedagogico = fguia.idformato))) JOIN conteudotipo ct ON ((f.idconteudotipo = ct.idconteudotipo))) WHERE (cd.flsitetematico = true);


ALTER TABLE public.vw_sitestematicos OWNER TO postgres;

--
-- TOC entry 2231 (class 2604 OID 115063)
-- Dependencies: 166 165
-- Name: idacessibilidade; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY acessibilidade ALTER COLUMN idacessibilidade SET DEFAULT nextval('acessibilidade_idacessibilidade_seq'::regclass);


--
-- TOC entry 2234 (class 2604 OID 115064)
-- Dependencies: 168 167
-- Name: idagendacomentario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY agendacomentario ALTER COLUMN idagendacomentario SET DEFAULT nextval('agendacomentario_idagendacomentario_seq'::regclass);


--
-- TOC entry 2238 (class 2604 OID 115065)
-- Dependencies: 171 170
-- Name: idalbumcomentario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY albumcomentario ALTER COLUMN idalbumcomentario SET DEFAULT nextval('albumcomentario_idalbumcomentario_seq'::regclass);


--
-- TOC entry 2247 (class 2604 OID 115066)
-- Dependencies: 185 184
-- Name: idblogcomentario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY blogcomentario ALTER COLUMN idblogcomentario SET DEFAULT nextval('blogcomentario_idblogcomentario_seq'::regclass);


--
-- TOC entry 2248 (class 2604 OID 115067)
-- Dependencies: 187 186
-- Name: idcanal; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY canal ALTER COLUMN idcanal SET DEFAULT nextval('canal_idcanal_seq'::regclass);


--
-- TOC entry 2249 (class 2604 OID 115068)
-- Dependencies: 189 188
-- Name: idcategoriacomponentecurricular; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY categoriacomponentecurricular ALTER COLUMN idcategoriacomponentecurricular SET DEFAULT nextval('categoriacomponentecurricular_idcategoriacomponentecurricul_seq'::regclass);


--
-- TOC entry 2252 (class 2604 OID 115069)
-- Dependencies: 191 190
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY chatmensagens ALTER COLUMN id SET DEFAULT nextval('chatmensagens_id_seq'::regclass);


--
-- TOC entry 2256 (class 2604 OID 115070)
-- Dependencies: 193 192
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY chatmensagensstatus ALTER COLUMN id SET DEFAULT nextval('chatmensagensstatus_id_seq'::regclass);


--
-- TOC entry 2261 (class 2604 OID 115071)
-- Dependencies: 197 196
-- Name: idcomponentecurriculartopico; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY componentecurriculartopico ALTER COLUMN idcomponentecurriculartopico SET DEFAULT nextval('componentecurriculartopico_idcomponentecurriculartopico_seq'::regclass);


--
-- TOC entry 2269 (class 2604 OID 115072)
-- Dependencies: 202 201
-- Name: idcomunidadeagenda; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comunidadeagenda ALTER COLUMN idcomunidadeagenda SET DEFAULT nextval('comunidadeagenda_idcomunidadeagenda_seq'::regclass);


--
-- TOC entry 2271 (class 2604 OID 115073)
-- Dependencies: 204 203
-- Name: idcomunidadealbum; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comunidadealbum ALTER COLUMN idcomunidadealbum SET DEFAULT nextval('comunidadealbum_idcomunidadealbum_seq'::regclass);


--
-- TOC entry 2273 (class 2604 OID 115074)
-- Dependencies: 206 205
-- Name: idcomunidadealbumfoto; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comunidadealbumfoto ALTER COLUMN idcomunidadealbumfoto SET DEFAULT nextval('comunidadealbumfoto_idcomunidadealbumfoto_seq'::regclass);


--
-- TOC entry 2275 (class 2604 OID 115075)
-- Dependencies: 208 207
-- Name: idcomunidadeblog; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comunidadeblog ALTER COLUMN idcomunidadeblog SET DEFAULT nextval('comunidadeblog_idcomunidadeblog_seq'::regclass);


--
-- TOC entry 2276 (class 2604 OID 115076)
-- Dependencies: 210 209
-- Name: idcomunidadefoto; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comunidadefoto ALTER COLUMN idcomunidadefoto SET DEFAULT nextval('comunidadefoto_idcomunidadefoto_seq'::regclass);


--
-- TOC entry 2287 (class 2604 OID 115077)
-- Dependencies: 220 219
-- Name: idcomuusuario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comuusuario ALTER COLUMN idcomuusuario SET DEFAULT nextval('comuusuario_idcomuusuario_seq'::regclass);


--
-- TOC entry 2304 (class 2604 OID 115078)
-- Dependencies: 226 225
-- Name: idconteudodigitalcategoria; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigitalcategoria ALTER COLUMN idconteudodigitalcategoria SET DEFAULT nextval('conteudodigitalcategoria_idconteudodigitalcategoria_seq'::regclass);


--
-- TOC entry 2309 (class 2604 OID 115079)
-- Dependencies: 236 235
-- Name: idconteudolicenca; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudolicenca ALTER COLUMN idconteudolicenca SET DEFAULT nextval('conteudolicenca_idconteudolicenca_seq'::regclass);


--
-- TOC entry 2314 (class 2604 OID 115080)
-- Dependencies: 242 241
-- Name: iddispositivo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dispositivo ALTER COLUMN iddispositivo SET DEFAULT nextval('dispositivo_iddispositivo_seq'::regclass);


--
-- TOC entry 2332 (class 2604 OID 115081)
-- Dependencies: 256 255
-- Name: idfeedcontagem; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY feedcontagem ALTER COLUMN idfeedcontagem SET DEFAULT nextval('feedcontagem_idfeedcontagem_seq'::regclass);


--
-- TOC entry 2334 (class 2604 OID 115082)
-- Dependencies: 258 257
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY feeddetalhe ALTER COLUMN id SET DEFAULT nextval('feeddetalhe_id_seq'::regclass);


--
-- TOC entry 2336 (class 2604 OID 115083)
-- Dependencies: 260 259
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY feedtabela ALTER COLUMN id SET DEFAULT nextval('feedtabela_id_seq'::regclass);


--
-- TOC entry 2337 (class 2604 OID 115084)
-- Dependencies: 262 261
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY feedtipo ALTER COLUMN id SET DEFAULT nextval('feedtipo_id_seq'::regclass);


--
-- TOC entry 2342 (class 2604 OID 115085)
-- Dependencies: 272 271
-- Name: idredesocial; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY redesocial ALTER COLUMN idredesocial SET DEFAULT nextval('redesocial_idredesocial_seq'::regclass);


--
-- TOC entry 2344 (class 2604 OID 115086)
-- Dependencies: 276 275
-- Name: idservidor; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servidor ALTER COLUMN idservidor SET DEFAULT nextval('servidor_idservidor_seq'::regclass);


--
-- TOC entry 2353 (class 2604 OID 115087)
-- Dependencies: 284 283
-- Name: idusuarioalbum; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarioalbum ALTER COLUMN idusuarioalbum SET DEFAULT nextval('usuarioalbum_idusuarioalbum_seq'::regclass);


--
-- TOC entry 2355 (class 2604 OID 115088)
-- Dependencies: 286 285
-- Name: idusuarioalbumfoto; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarioalbumfoto ALTER COLUMN idusuarioalbumfoto SET DEFAULT nextval('usuarioalbumfoto_idusuarioalbumfoto_seq'::regclass);


--
-- TOC entry 2363 (class 2604 OID 115090)
-- Dependencies: 293 292
-- Name: idusuariofoto; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuariofoto ALTER COLUMN idusuariofoto SET DEFAULT nextval('usuariofoto_idusuariofoto_seq'::regclass);


--
-- TOC entry 2368 (class 2604 OID 115091)
-- Dependencies: 297 296
-- Name: idusuarioredesocial; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarioredesocial ALTER COLUMN idusuarioredesocial SET DEFAULT nextval('usuarioredesocial_idusuarioredesocial_seq'::regclass);


--
-- TOC entry 2786 (class 0 OID 114510)
-- Dependencies: 165 2922
-- Data for Name: acessibilidade; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY acessibilidade (idacessibilidade, nomeacessibilidade, descricaoacessibilidade, idacessibilidadepai, datacriacao) FROM stdin;
\.


--
-- TOC entry 3108 (class 0 OID 0)
-- Dependencies: 166
-- Name: acessibilidade_idacessibilidade_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('acessibilidade_idacessibilidade_seq', 1, false);


--
-- TOC entry 2788 (class 0 OID 114519)
-- Dependencies: 167 2922
-- Data for Name: agendacomentario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY agendacomentario (idagendacomentario, idagenda, mensagem, tipo, datacriacao, idusuario) FROM stdin;
\.


--
-- TOC entry 3109 (class 0 OID 0)
-- Dependencies: 168
-- Name: agendacomentario_idagendacomentario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('agendacomentario_idagendacomentario_seq', 9, true);


--
-- TOC entry 3110 (class 0 OID 0)
-- Dependencies: 169
-- Name: album_idalbum_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('album_idalbum_seq', 1, false);


--
-- TOC entry 2791 (class 0 OID 114531)
-- Dependencies: 170 2922
-- Data for Name: albumcomentario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY albumcomentario (idalbumcomentario, idusuarioalbumfoto, mensagem, tipocomentario, tipoalbum, datacriacao, idusuario, visto) FROM stdin;
\.


--
-- TOC entry 3111 (class 0 OID 0)
-- Dependencies: 171
-- Name: albumcomentario_idalbumcomentario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('albumcomentario_idalbumcomentario_seq', 60, true);


--
-- TOC entry 3112 (class 0 OID 0)
-- Dependencies: 172
-- Name: albumfoto_idalbumfoto_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('albumfoto_idalbumfoto_seq', 1, false);


--
-- TOC entry 2795 (class 0 OID 114546)
-- Dependencies: 174 2922
-- Data for Name: ambientedeapoio; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ambientedeapoio (idambientedeapoio, idambientedeapoiocategoria, titulo, url, urlprojeto, descricao, usopedagogico, acessos, fldestaque, idusuariopublicador) FROM stdin;
\.


--
-- TOC entry 3113 (class 0 OID 0)
-- Dependencies: 173
-- Name: ambientedeapoio_idambientedeapoio_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ambientedeapoio_idambientedeapoio_seq', 118, true);


--
-- TOC entry 2797 (class 0 OID 114557)
-- Dependencies: 176 2922
-- Data for Name: ambientedeapoiocategoria; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ambientedeapoiocategoria (idambientedeapoiocategoria, nomeambientedeapoiocategoria) FROM stdin;
\.


--
-- TOC entry 3114 (class 0 OID 0)
-- Dependencies: 175
-- Name: ambientedeapoiocategoria_idambientedeapoiocategoria_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ambientedeapoiocategoria_idambientedeapoiocategoria_seq', 39, true);


--
-- TOC entry 2799 (class 0 OID 114563)
-- Dependencies: 178 2922
-- Data for Name: ambientedeapoiocomentario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ambientedeapoiocomentario (idambientedeapoiocomentario, idambientedeapoio, idusuario, comentario, datacriacao) FROM stdin;
\.


--
-- TOC entry 3115 (class 0 OID 0)
-- Dependencies: 177
-- Name: ambientedeapoiocomentario_idambientedeapoiocomentario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ambientedeapoiocomentario_idambientedeapoiocomentario_seq', 1, true);


--
-- TOC entry 2800 (class 0 OID 114570)
-- Dependencies: 179 2922
-- Data for Name: ambientedeapoiofavorito; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ambientedeapoiofavorito (idambientedeapoio, idfavorito) FROM stdin;
\.


--
-- TOC entry 2801 (class 0 OID 114573)
-- Dependencies: 180 2922
-- Data for Name: ambientedeapoiotag; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ambientedeapoiotag (idambientedeapoio, idtag) FROM stdin;
\.


--
-- TOC entry 3116 (class 0 OID 0)
-- Dependencies: 181
-- Name: ava_idava_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ava_idava_seq', 14, true);


--
-- TOC entry 3117 (class 0 OID 0)
-- Dependencies: 182
-- Name: avacomentario_idavacomentario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('avacomentario_idavacomentario_seq', 6, true);


--
-- TOC entry 3118 (class 0 OID 0)
-- Dependencies: 183
-- Name: blog_idblog_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('blog_idblog_seq', 1, false);


--
-- TOC entry 2805 (class 0 OID 114582)
-- Dependencies: 184 2922
-- Data for Name: blogcomentario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY blogcomentario (idblogcomentario, idblog, mensagem, tipo, datacriacao, idusuario, visto) FROM stdin;
\.


--
-- TOC entry 3119 (class 0 OID 0)
-- Dependencies: 185
-- Name: blogcomentario_idblogcomentario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('blogcomentario_idblogcomentario_seq', 36, true);


--
-- TOC entry 2807 (class 0 OID 114593)
-- Dependencies: 186 2922
-- Data for Name: canal; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY canal (idcanal, nomecanal) FROM stdin;
1	TV Anísio Teixeira
2	EMITEC - Ensino Médio com Intermediação Tecnológica
\.


--
-- TOC entry 3120 (class 0 OID 0)
-- Dependencies: 187
-- Name: canal_idcanal_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('canal_idcanal_seq', 1, true);


--
-- TOC entry 2809 (class 0 OID 114598)
-- Dependencies: 188 2922
-- Data for Name: categoriacomponentecurricular; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY categoriacomponentecurricular (idcategoriacomponentecurricular, nomecategoriacomponentecurricular) FROM stdin;
1	Áreas de Conhecimento
2	Linguagens Artísticas
3	Temas Transversais
5	Séries - Ensino Fundamental
6	Séries - Ensino Médio
4	Séries - Educação Infantil
\.


--
-- TOC entry 3121 (class 0 OID 0)
-- Dependencies: 189
-- Name: categoriacomponentecurricular_idcategoriacomponentecurricul_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('categoriacomponentecurricular_idcategoriacomponentecurricul_seq', 1, false);


--
-- TOC entry 2811 (class 0 OID 114603)
-- Dependencies: 190 2922
-- Data for Name: chatmensagens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY chatmensagens (id, id_de, id_para, mensagem, data, lido) FROM stdin;
\.


--
-- TOC entry 3122 (class 0 OID 0)
-- Dependencies: 191
-- Name: chatmensagens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('chatmensagens_id_seq', 1583, true);


--
-- TOC entry 2813 (class 0 OID 114613)
-- Dependencies: 192 2922
-- Data for Name: chatmensagensstatus; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY chatmensagensstatus (id, id_de, id_para, flavisar, flbloquear, dataultimamensagem) FROM stdin;
\.


--
-- TOC entry 3123 (class 0 OID 0)
-- Dependencies: 193
-- Name: chatmensagensstatus_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('chatmensagensstatus_id_seq', 211, true);


--
-- TOC entry 2816 (class 0 OID 114623)
-- Dependencies: 195 2922
-- Data for Name: componentecurricular; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY componentecurricular (idcomponentecurricular, idnivelensino, nomecomponentecurricular, idcategoriacomponentecurricular) FROM stdin;
57	7	Recursos Naturais	\N
4	3	Educação Física	\N
5	3	Orientação Sexual	\N
6	3	Alfabetização	\N
7	3	Artes	\N
8	3	Ética	\N
9	3	Matemática	\N
10	3	Pluralidade Cultural	\N
11	3	Saúde	\N
12	3	Meio Ambiente 	\N
13	3	Língua Portuguesa	\N
14	3	Geografia	\N
15	3	História	\N
16	3	Ciências Naturais	\N
17	4	Educação Física	\N
18	4	Orientação Sexual	\N
19	4	Artes	\N
20	4	Língua Estrangeira	\N
21	4	Matemática	\N
22	4	Pluralidade Cultural	\N
23	4	Saúde	\N
24	4	Meio ambiente	\N
25	4	Língua Portuguesa	\N
26	4	Geografia	\N
27	4	História 	\N
28	4	Ciências Naturais	\N
29	5	Educação Física	\N
30	5	Física	\N
31	5	Literatura	\N
32	5	Artes	\N
33	5	Biologia	\N
34	5	Filosofia	\N
35	5	Língua Estrangeira	\N
36	5	Matemática	\N
37	5	Sociologia	\N
38	5	Língua Portuguesa	\N
39	5	Química	\N
40	5	Geografia	\N
41	5	História	\N
42	6	Arte Visual	\N
43	6	Movimento	\N
44	6	Matemática	\N
45	6	Natureza e Sociedade	\N
46	6	Linguagem oral e escrita	\N
47	7	Gestão e Negócios	\N
48	7	Informação e Comunicação	\N
49	7	Produção Cultural e Design	\N
50	7	Ambiente, Saúde e Segurança	\N
51	7	Apoio Escolar	\N
52	7	Hospitalidade e Lazer	\N
53	7	Produção Alimentícia	\N
54	7	Produção Industrial	\N
55	7	Controle e Processos Industriais	\N
56	7	Infra-estrutura	\N
58	8	Ciências Agrárias	\N
59	8	Ciências Biológicas	\N
60	8	Ciências da Saúde	\N
61	8	Ciências Exatas e da Terra	\N
62	8	Ciências Humanas	\N
63	8	Ciência Sociais Aplicadas	\N
64	8	Engenharias	\N
65	8	Interdisciplinar	\N
66	8	Lingüística, Letras e Artes	\N
67	9	Educação Física	\N
68	9	Artes	\N
69	9	Língua Portuguesa	\N
70	9	Outros	\N
71	9	História	\N
72	9	Língua Estrangeira	\N
73	9	Matemática	\N
74	9	Ciências Naturais	\N
75	10	Estudo da Sociedade e da Natureza	\N
76	10	Matemática	\N
77	10	Língua Portuguesa	\N
78	11	Educação Física	\N
79	11	Artes	\N
80	11	Línguas	\N
81	11	Ciências	\N
82	11	Geografia	\N
83	11	História	\N
84	\N	Ciências da natureza	1
85	\N	Humanas	1
86	\N	Linguagens e seus códigos	1
87	\N	Matemática	1
88	\N	Dança	2
89	\N	Artes Visuais	2
90	\N	Audiovisual	2
91	\N	Música	2
92	\N	Teatro	2
93	\N	Circo	2
94	\N	Literatura	2
95	\N	Pluralidade Cultural	3
96	\N	Saúde	3
97	\N	Gênero e Sexualidade	3
98	\N	Ética e Cidadania	3
99	\N	Educação Especial	3
100	\N	Trabalho e Consumo	3
101	\N	História e Cultura Africana	3
102	\N	História e Cultura Indígena	3
103	\N	Educação Ambiental	3
104	\N	Creche	4
105	\N	Pré-Escola	4
106	\N	1º Ano	5
107	\N	2º Ano	5
108	\N	3º Ano	5
109	\N	4º Ano	5
110	\N	5º Ano	5
111	\N	6º Ano	5
112	\N	7º Ano	5
113	\N	8º Ano	5
114	\N	9º Ano	5
115	\N	1º Ano	6
116	\N	2º Ano	6
117	\N	3º Ano	6
\.


--
-- TOC entry 3124 (class 0 OID 0)
-- Dependencies: 194
-- Name: componentecurricular_idcomponentecurricular_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('componentecurricular_idcomponentecurricular_seq', 103, true);


--
-- TOC entry 2817 (class 0 OID 114627)
-- Dependencies: 196 2922
-- Data for Name: componentecurriculartopico; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY componentecurriculartopico (idcomponentecurriculartopico, idcomponentecurricular, nomecomponentecurriculartopico, urlcomponentecurriculartopico, idcomponentecurriculartopicopai, flvisivel, flativo, datacriacao) FROM stdin;
540	34	ENSINO MÉDIO – 1.ª Série	\N	\N	t	t	2014-10-24 15:47:42.955612
548	34	O que é mito? Os rituais, Teorias sobre o mito	\N	540	t	t	2014-10-24 15:47:42.957814
552	34	Dogmatismo, ceticismo e relativismo no pensamento grego:	\N	548	t	t	2014-10-24 15:47:42.9586
1616	38	ENSINO MÉDIO – 3ª série	\N	\N	t	t	2014-10-24 15:47:42.959183
957	29	ENSINO MEDIO - 1ª SERIE	\N	\N	t	t	2014-10-24 15:47:42.959646
1959	32	ENSINO MEDIO -1.ª 2.ª e 3.ª Série	\N	\N	t	t	2014-10-24 15:47:42.9602
293	29	JOGO	\N	957	t	t	2014-10-24 15:47:42.960644
294	29	Jogos em família	http://bit.ly/1d4Hdxf	293	t	t	2014-10-24 15:47:42.961196
295	29	Jogos populares	http://bit.ly/1d4Hdxf	293	t	t	2014-10-24 15:47:42.961732
296	29	Jogos de salão	\N	293	t	t	2014-10-24 15:47:42.962334
297	29	Jogos de tabuleiros	http://bit.ly/1d4Hdxf	293	t	t	2014-10-24 15:47:42.962794
298	29	Jogos de mesa	\N	293	t	t	2014-10-24 15:47:42.963394
299	29	Jogos de rua	http://bit.ly/1mFK6HR	293	t	t	2014-10-24 15:47:42.963867
300	29	Jogos pré desportivos e lúdicos	http://bit.ly/1dnTnli	293	t	t	2014-10-24 15:47:42.964386
301	29	DANÇA	http://bit.ly/1gWrafQ	957	t	t	2014-10-24 15:47:42.964888
302	29	Danças indígenas	http://bit.ly/1peCYPL	301	t	t	2014-10-24 15:47:42.965398
303	29	Danças africanas	http://bit.ly/1d4oNwu	301	t	t	2014-10-24 15:47:42.965857
304	29	Danças européias	http://bit.ly/1gWrafQ	301	t	t	2014-10-24 15:47:42.966342
305	29	Danças latino americanas	http://bit.ly/1dnTnli	301	t	t	2014-10-24 15:47:42.966834
306	29	Danças asiáticas	http://bit.ly/1gWrafQ	301	t	t	2014-10-24 15:47:42.967334
307	29	Dança em grupos	http://bit.ly/1peCYPL	301	t	t	2014-10-24 15:47:42.967794
308	29	Danças tribais	http://bit.ly/1gI8CEs	301	t	t	2014-10-24 15:47:42.968378
309	29	ESPORTE	http://bit.ly/1pfEEsi	957	t	t	2014-10-24 15:47:42.968856
310	29	Jogos populares	http://bit.ly/1sN4GdG	309	t	t	2014-10-24 15:47:42.969358
311	29	Esporte popular	http://bit.ly/1sN4GdG	309	t	t	2014-10-24 15:47:42.969752
312	29	Atletismo	http://bit.ly/1gIwlEy	309	t	t	2014-10-24 15:47:42.970363
313	29	GINÁSTICA	http://bit.ly/1pfEEsi	957	t	t	2014-10-24 15:47:42.970769
314	29	Apoio e giro	http://bit.ly/1pczEog	313	t	t	2014-10-24 15:47:42.971324
315	29	Ginástica geral	http://bit.ly/1pczEog	313	t	t	2014-10-24 15:47:42.971711
316	29	CAPOEIRA	http://bit.ly/1pfEEsi	957	t	t	2014-10-24 15:47:42.97217
317	29	Jogo	http://bit.ly/1r43g82	316	t	t	2014-10-24 15:47:42.972561
318	29	Dança	http://bit.ly/1r43g82	316	t	t	2014-10-24 15:47:42.972946
319	29	Patrimônio imaterial da humanidade	http://bit.ly/1r43g82	316	t	t	2014-10-24 15:47:42.97335
320	29	ATIVIDADES AQUÁTICAS	http://bit.ly/1pfEEsi	957	t	t	2014-10-24 15:47:42.973738
321	29	Jogos aquáticos	http://bit.ly/1r43g82	320	t	t	2014-10-24 15:47:42.974177
322	29	Nado costa	http://bit.ly/1pfNlmo	320	t	t	2014-10-24 15:47:42.97457
323	29	Nado crawl	http://bit.ly/1pfNlmo	320	t	t	2014-10-24 15:47:42.974954
324	29	Nado peito	\N	320	t	t	2014-10-24 15:47:42.975367
325	29	Nado golfinho	\N	320	t	t	2014-10-24 15:47:42.975752
326	29	Nado medley	\N	326	t	t	2014-10-24 15:47:42.976295
327	29	Saltos	\N	326	t	t	2014-10-24 15:47:42.976686
328	29	ENSINO MÉDIO – 2.ª Série	\N	\N	t	t	2014-10-24 15:47:42.977166
329	29	JOGO	http://bit.ly/1pfEEsi	328	t	t	2014-10-24 15:47:42.977545
330	29	Jogos de rede	http://bit.ly/ZgJajO	329	t	t	2014-10-24 15:47:42.977933
331	29	Jogos de épocas	http://bit.ly/1d4oNwu	329	t	t	2014-10-24 15:47:42.978349
332	29	Jogos dos povos indígenas	http://bit.ly/1hr4Bkn	329	t	t	2014-10-24 15:47:42.978759
333	29	Jogos de raízes africanas	http://bit.ly/1mFK6HR	329	t	t	2014-10-24 15:47:42.979289
334	29	Jogos de comunidades quilombolas	http://bit.ly/1mFK6HR	329	t	t	2014-10-24 15:47:42.979684
335	29	Jogos do campo	http://bit.ly/1pfyJn5	329	t	t	2014-10-24 15:47:42.980166
336	29	Jogos pré desportivos e lúdicos	http://bit.ly/1i090fJ	329	t	t	2014-10-24 15:47:42.980557
337	29	DANÇA	http://bit.ly/1pfEEsi	328	t	t	2014-10-24 15:47:42.980939
338	29	Danças feudais	http://bit.ly/1gWrafQ	337	t	t	2014-10-24 15:47:42.981346
339	29	Dança norte americana	http://bit.ly/1pfNlmo	337	t	t	2014-10-24 15:47:42.981727
340	29	Dança de salão	http://bit.ly/1mG0doZ	337	t	t	2014-10-24 15:47:42.982178
341	29	Danças clássicas	http://bit.ly/1gWrafQ	337	t	t	2014-10-24 15:47:42.982566
342	29	Danças contemporâneas	http://bit.ly/1gWrafQ	337	t	t	2014-10-24 15:47:42.982952
343	29	ESPORTE	http://bit.ly/1pfEEsi	328	t	t	2014-10-24 15:47:42.983357
344	29	Esportes individuais	http://bit.ly/1gIwlEy	343	t	t	2014-10-24 15:47:42.983744
345	29	Esportes coletivos	http://bit.ly/1sN4GdG	343	t	t	2014-10-24 15:47:42.984168
346	29	Esportes tradicionais das comunidades de diferentes origens étnicas e territoriais	http://bit.ly/1gIwlEy	343	t	t	2014-10-24 15:47:42.984561
347	29	Esporte de quadra	http://bit.ly/1sN4GdG	343	t	t	2014-10-24 15:47:42.984952
348	29	Esporte de campo	http://bit.ly/1sN4GdG	343	t	t	2014-10-24 15:47:42.985353
349	29	GINÁSTICA	http://bit.ly/1pfEEsi	343	t	t	2014-10-24 15:47:42.98574
350	29	Artística	http://bit.ly/1pczEog	343	t	t	2014-10-24 15:47:42.986162
351	29	Rítmica	http://bit.ly/1pczEog	343	t	t	2014-10-24 15:47:42.98655
352	29	CAPOEIRA	http://bit.ly/1pfEEsi	328	t	t	2014-10-24 15:47:42.986934
353	29	Jogo	http://bit.ly/1r43g82	352	t	t	2014-10-24 15:47:42.987338
354	29	Dança	http://bit.ly/1r43g82	352	t	t	2014-10-24 15:47:42.987735
355	29	Patrimônio imaterial da humanidade	http://bit.ly/1r43g82	352	t	t	2014-10-24 15:47:42.988177
356	29	ATIVIDADES AQUÁTICAS	http://bit.ly/1pfEEsi	328	t	t	2014-10-24 15:47:42.988568
357	29	Jogos aquáticos com material	http://bit.ly/1pfNlmo	356	t	t	2014-10-24 15:47:42.988954
358	29	Canoagem	\N	356	t	t	2014-10-24 15:47:42.989366
359	29	Pólo aquático	\N	356	t	t	2014-10-24 15:47:42.989756
360	29	ENSINO MÉDIO – 3.ª Série	\N	\N	t	t	2014-10-24 15:47:42.990291
361	29	JOGO	http://bit.ly/1pfEEsi	360	t	t	2014-10-24 15:47:42.990672
362	29	Jogos radicais	http://bit.ly/1i0a7fh	361	t	t	2014-10-24 15:47:42.991173
363	29	Jogos aquáticos	\N	361	t	t	2014-10-24 15:47:42.991564
364	29	Jogos circenses	http://bit.ly/1hr4Bkn	361	t	t	2014-10-24 15:47:42.991946
365	29	Jogos locais, regionais, nacionais e internacionais	http://bit.ly/1i0a7fh	361	t	t	2014-10-24 15:47:42.992351
366	29	Jogos pré desportivos e lúdicos	http://bit.ly/1i090fJ	361	t	t	2014-10-24 15:47:42.992744
367	29	DANÇA	http://bit.ly/1pfEEsi	360	t	t	2014-10-24 15:47:42.993182
368	29	Danças contemporâneas:	http://bit.ly/1gWrafQ	367	t	t	2014-10-24 15:47:42.993575
369	29	Ballet	http://bit.ly/1gWrafQ	367	t	t	2014-10-24 15:47:42.993959
370	29	Jazz	http://bit.ly/1gWrafQ	367	t	t	2014-10-24 15:47:42.994388
371	29	Dança moderna	http://bit.ly/1gWrafQ	367	t	t	2014-10-24 15:47:42.994781
372	29	Dança de rua	http://bit.ly/1pfNlmo	367	t	t	2014-10-24 15:47:42.99531
373	29	Danças próprias do capitalismo contemporâneo	http://bit.ly/1pfNlmo	367	t	t	2014-10-24 15:47:42.995706
374	29	Danças brasileiras:	http://bit.ly/1peCYPL	367	t	t	2014-10-24 15:47:42.996175
974	32	Expressionismo	\N	958	t	t	2014-10-24 15:47:43.070719
375	29	Danças nordestina	http://bit.ly/1peCYPL	367	t	t	2014-10-24 15:47:42.996567
376	29	Danças do norte	http://bit.ly/1peCYPL	367	t	t	2014-10-24 15:47:42.997011
377	29	Danças do sul	http://bit.ly/1peCYPL	367	t	t	2014-10-24 15:47:42.997408
378	29	Danças do sudeste	http://bit.ly/1peCYPL	367	t	t	2014-10-24 15:47:42.997794
379	29	Danças do centro-oeste	http://bit.ly/1peCYPL	367	t	t	2014-10-24 15:47:42.998262
380	29	Danças de origens do campo relacionadas ao mundo trabalho.	http://bit.ly/1h3emFi	367	t	t	2014-10-24 15:47:42.99865
381	29	ESPORTE	http://bit.ly/1pfEEsi	360	t	t	2014-10-24 15:47:42.999163
382	29	Esportes de espetáculos	http://bit.ly/1sN4GdG	381	t	t	2014-10-24 15:47:42.999551
383	29	Esporte olímpico	http://bit.ly/1gIwlEy	381	t	t	2014-10-24 15:47:42.999938
384	29	Esporte paraolímpico	http://bit.ly/1sN4GdG	381	t	t	2014-10-24 15:47:43.000347
385	29	GINÁSTICA	http://bit.ly/1pfEEsi	360	t	t	2014-10-24 15:47:43.000736
386	29	Ginástica acrobática	http://bit.ly/1pczEog	385	t	t	2014-10-24 15:47:43.001173
387	29	Ginástica circense	http://bit.ly/1pczEog	385	t	t	2014-10-24 15:47:43.001569
388	29	Ginástica aeróbica	http://bit.ly/1pczEog	385	t	t	2014-10-24 15:47:43.001956
389	29	Ginástica compensatória	http://bit.ly/1pczEog	385	t	t	2014-10-24 15:47:43.002355
390	29	Ginástica laboral	http://bit.ly/1pczEog	385	t	t	2014-10-24 15:47:43.002745
391	29	Ginástica de trampolim	http://bit.ly/1pczEog	385	t	t	2014-10-24 15:47:43.003188
392	29	Macro ginástica com fins estéticos	http://bit.ly/1pczEog	385	t	t	2014-10-24 15:47:43.003577
393	29	Macro ginástica com fins de treinamento	http://bit.ly/1pczEog	385	t	t	2014-10-24 15:47:43.003959
394	29	Macro ginástica com fins compensatórios	http://bit.ly/1pczEog	385	t	t	2014-10-24 15:47:43.004357
395	29	Macro ginástica com fins postural	http://bit.ly/1pczEog	385	t	t	2014-10-24 15:47:43.004746
396	29	CAPOEIRA	http://bit.ly/1pfEEsi	360	t	t	2014-10-24 15:47:43.005196
397	29	Jogo	http://bit.ly/1r43g82	396	t	t	2014-10-24 15:47:43.005576
398	29	Dança	http://bit.ly/1r43g82	396	t	t	2014-10-24 15:47:43.00596
399	29	Patrimônio imaterial da humanidade	http://bit.ly/1r43g82	396	t	t	2014-10-24 15:47:43.006348
400	29	ATIVIDADES AQUÁTICAS	http://bit.ly/1pfEEsi	360	t	t	2014-10-24 15:47:43.006741
401	29	Mergulho	\N	400	t	t	2014-10-24 15:47:43.007178
402	29	Surf	\N	400	t	t	2014-10-24 15:47:43.007573
403	30	ENSINO MÉDIO – 1.ª Série	\N	\N	t	t	2014-10-24 15:47:43.007962
404	30	Os métodos da Ciência Física	\N	403	t	t	2014-10-24 15:47:43.008371
405	30	O método cientifico	http://bit.ly/1pwAgEI	404	t	t	2014-10-24 15:47:43.008755
406	30	Modelo, teorias, leis e princípios	\N	404	t	t	2014-10-24 15:47:43.009187
407	30	Sistema Internacional de Unidades (SI)	http://bit.ly/1rqj1dq	404	t	t	2014-10-24 15:47:43.009584
408	30	A precisão das medidas	\N	404	t	t	2014-10-24 15:47:43.009965
409	30	Ordem de grandeza – estimativa de valores	http://bit.ly/1nIAby2	404	t	t	2014-10-24 15:47:43.010387
410	30	Cinemática (Movimentos):	\N	403	t	t	2014-10-24 15:47:43.010802
411	30	Velocidade média	http://bit.ly/1pwCHqT	410	t	t	2014-10-24 15:47:43.011229
412	30	MRU	http://bit.ly/1uPEChQ	410	t	t	2014-10-24 15:47:43.01162
413	30	MRUV	http://bit.ly/1rqmTLM	410	t	t	2014-10-24 15:47:43.01201
414	30	Queda livre	\N	410	t	t	2014-10-24 15:47:43.012407
415	30	Lançamento vertical	http://bit.ly/1rqn6hV	410	t	t	2014-10-24 15:47:43.012793
416	30	Lançamento horizontal	http://bit.ly/1wNMRJo	410	t	t	2014-10-24 15:47:43.013271
417	30	Lançamento oblíquo	http://bit.ly/1pwDTKT	410	t	t	2014-10-24 15:47:43.013659
418	30	Movimento circular	http://bit.ly/1wNMWwP	410	t	t	2014-10-24 15:47:43.014065
419	30	Cinemática vetorial	\N	410	t	t	2014-10-24 15:47:43.014429
420	30	Dinâmica (Força e Energia):	\N	403	t	t	2014-10-24 15:47:43.014812
421	30	Leis de Newton:	\N	420	t	t	2014-10-24 15:47:43.015296
422	30	Princípio da inércia	http://bit.ly/1wNMZbZ	421	t	t	2014-10-24 15:47:43.015687
423	30	O principio fundamental da Dinâmica	http://bit.ly/1wejXox	421	t	t	2014-10-24 15:47:43.016167
424	30	O principio da ação e reação	http://bit.ly/1wNOBSU	421	t	t	2014-10-24 15:47:43.016561
425	30	Dinâmica do movimento circular	\N	421	t	t	2014-10-24 15:47:43.016963
426	30	Plano inclinado	http://bit.ly/1rQ9X3P	421	t	t	2014-10-24 15:47:43.017377
427	30	Forças de atrito	http://bit.ly/1utTyCy	421	t	t	2014-10-24 15:47:43.017761
428	30	Lei de Hooke	http://bit.ly/1wNOZAZ	421	t	t	2014-10-24 15:47:43.018285
429	30	Trabalho e energia:	\N	420	t	t	2014-10-24 15:47:43.018667
430	30	Trabalho de uma força	http://bit.ly/1utTUJh	429	t	t	2014-10-24 15:47:43.01917
431	30	Teorema da energia cinética	\N	429	t	t	2014-10-24 15:47:43.01956
432	30	Potência e rendimento	\N	429	t	t	2014-10-24 15:47:43.019948
433	30	Energia cinética	http://bit.ly/1p7k7F8	429	t	t	2014-10-24 15:47:43.020353
434	30	Energia potencial	http://bit.ly/1utUFCj	429	t	t	2014-10-24 15:47:43.020733
435	30	Energia potencial elástica	\N	429	t	t	2014-10-24 15:47:43.021173
436	30	Conservação da energia	http://bit.ly/X5kdri	429	t	t	2014-10-24 15:47:43.021569
437	30	Teorema da energia mecânica	\N	429	t	t	2014-10-24 15:47:43.021959
438	30	Impulso e quantidade de movimento:	\N	420	t	t	2014-10-24 15:47:43.022373
439	30	Impulso	http://bit.ly/1utVqLz	438	t	t	2014-10-24 15:47:43.022755
440	30	Quantidade de movimento	http://bit.ly/1utVqLz	438	t	t	2014-10-24 15:47:43.023294
441	30	Teorema do impulso	\N	438	t	t	2014-10-24 15:47:43.023688
442	30	Conservação da quantidade de movimento	http://bit.ly/1utVqLz	438	t	t	2014-10-24 15:47:43.024177
443	30	Colisões	http://bit.ly/1wNQgIb	438	t	t	2014-10-24 15:47:43.024566
444	30	Centro de massa	http://bit.ly/1wNQkrt	420	t	t	2014-10-24 15:47:43.024953
445	30	Hidrostática	\N	403	t	t	2014-10-24 15:47:43.025374
446	30	Densidade	http://bit.ly/Vq2F7y	445	t	t	2014-10-24 15:47:43.025758
447	30	Massa específica	\N	445	t	t	2014-10-24 15:47:43.02629
448	30	Pressão	http://bit.ly/1q1u8Vt	445	t	t	2014-10-24 15:47:43.026677
449	30	Principio de Stevin	\N	445	t	t	2014-10-24 15:47:43.027163
450	30	Principio de Arquimedes	http://bit.ly/1qNCks9	445	t	t	2014-10-24 15:47:43.027554
451	30	Conceito de densidade	http://bit.ly/Vq2F7y	445	t	t	2014-10-24 15:47:43.027946
452	30	O principio de Pascal	\N	445	t	t	2014-10-24 15:47:43.028341
453	30	Fluidos não newtonianos	\N	445	t	t	2014-10-24 15:47:43.028724
454	30	Gravitação Universal	\N	403	t	t	2014-10-24 15:47:43.029177
455	30	As leis de Kepler	http://bit.ly/1qNDpAp	454	t	t	2014-10-24 15:47:43.029574
456	30	A lei da Gravitação Universal	\N	454	t	t	2014-10-24 15:47:43.029965
457	30	A aceleração da gravidade	http://bit.ly/1rxsnnP	454	t	t	2014-10-24 15:47:43.030389
458	30	Máquina Simples	\N	403	t	t	2014-10-24 15:47:43.030775
459	30	Alavancas	http://bit.ly/1xtG1vY	458	t	t	2014-10-24 15:47:43.031278
460	30	Polias ou roldanas	\N	458	t	t	2014-10-24 15:47:43.031665
461	30	Transmissão de movimento circular e engrenagem	\N	458	t	t	2014-10-24 15:47:43.032166
462	30	ENSINO MÉDIO – 2.ª Série	\N	\N	t	t	2014-10-24 15:47:43.032559
463	30	Termologia	\N	462	t	t	2014-10-24 15:47:43.032935
464	30	Calorimetria (Calor):	\N	463	t	t	2014-10-24 15:47:43.033337
465	30	Calor sensível	http://bit.ly/1rxt54r	464	t	t	2014-10-24 15:47:43.033758
466	30	Calor Latente	http://bit.ly/1qNEjNo	464	t	t	2014-10-24 15:47:43.034302
467	30	Teorema das trocas de calor	\N	464	t	t	2014-10-24 15:47:43.034691
468	30	Curvas de aquecimento	\N	464	t	t	2014-10-24 15:47:43.035167
469	30	Propagação do calor:	\N	464	t	t	2014-10-24 15:47:43.035556
470	30	Condução	http://bit.ly/1m9o50i	468	t	t	2014-10-24 15:47:43.035945
471	30	Convecção	http://bit.ly/1rxtKmw	468	t	t	2014-10-24 15:47:43.036349
472	30	Irradiação	http://bit.ly/1qNFfBk	468	t	t	2014-10-24 15:47:43.03673
473	30	Termodinâmica	\N	462	t	t	2014-10-24 15:47:43.037178
474	30	Transformações gasosas	http://bit.ly/1xtH0fE	473	t	t	2014-10-24 15:47:43.037566
475	30	Trabalho de um gás	http://bit.ly/1qNFufA	473	t	t	2014-10-24 15:47:43.03795
476	30	1°lei da termodinâmica	http://bit.ly/1qNGU9S	473	t	t	2014-10-24 15:47:43.03835
477	30	Transformações cíclicas	http://bit.ly/1xtH0fE	473	t	t	2014-10-24 15:47:43.038745
478	30	Ciclo de Carnot	http://bit.ly/1rxv8Wi	473	t	t	2014-10-24 15:47:43.03917
479	30	2° Lei da termodinâmica	http://bit.ly/1xtHJ0f	473	t	t	2014-10-24 15:47:43.039553
480	30	Termometria	\N	463	t	t	2014-10-24 15:47:43.039938
481	30	Escalas termométricas	http://bit.ly/1pBJbVl	480	t	t	2014-10-24 15:47:43.040341
482	30	Dilação Térmica:	\N	463	t	t	2014-10-24 15:47:43.040736
483	30	Dilatação linear	\N	482	t	t	2014-10-24 15:47:43.041183
484	30	Dilatação superficial	\N	482	t	t	2014-10-24 15:47:43.041572
485	30	Dilatação volumétrica	\N	482	t	t	2014-10-24 15:47:43.041951
486	30	Dilatação dos líquidos	\N	482	t	t	2014-10-24 15:47:43.042339
487	30	Ondulatória	\N	462	t	t	2014-10-24 15:47:43.042724
488	30	Onda:	\N	487	t	t	2014-10-24 15:47:43.04318
489	30	Sistemas oscilantes	http://bit.ly/1xtIYwB	488	t	t	2014-10-24 15:47:43.043572
490	30	MHS	http://bit.ly/1pBKKCG	488	t	t	2014-10-24 15:47:43.043951
491	30	Fenômenos ondulatórios	http://bit.ly/1pBN3WA	488	t	t	2014-10-24 15:47:43.044342
492	30	Acústica	http://bit.ly/1xtJ8Ec	488	t	t	2014-10-24 15:47:43.044724
493	30	Ondas Eletromagnéticas	http://bit.ly/1rxybxP	488	t	t	2014-10-24 15:47:43.045178
494	30	Eletricidade	\N	462	t	t	2014-10-24 15:47:43.045571
495	30	Eletrostática:	\N	494	t	t	2014-10-24 15:47:43.045951
496	30	Processos de eletrização	http://bit.ly/1xtJgUb	495	t	t	2014-10-24 15:47:43.046348
497	30	Lei de Coulomb	http://bit.ly/1xtJnPD	495	t	t	2014-10-24 15:47:43.04673
498	30	Campo elétrico	http://bit.ly/1m9o50i	495	t	t	2014-10-24 15:47:43.047153
499	30	Potencial elétrico	http://bit.ly/1rxyJ6P	495	t	t	2014-10-24 15:47:43.047537
500	30	Superfícies equipotenciais	http://bit.ly/1rxySap	495	t	t	2014-10-24 15:47:43.04793
501	30	Capacitores	http://bit.ly/1xtJJ8K	495	t	t	2014-10-24 15:47:43.048337
502	30	Eletrodinâmica:	\N	494	t	t	2014-10-24 15:47:43.048726
503	30	Corrente elétrica	http://bit.ly/1pBOF2z	502	t	t	2014-10-24 15:47:43.049174
504	30	d.d.p	http://bit.ly/1pBOXqe	502	t	t	2014-10-24 15:47:43.049565
505	30	Leis de Ohm	http://bit.ly/1xtJV7T	502	t	t	2014-10-24 15:47:43.04995
506	30	Potência dissipada por resistência	http://bit.ly/1xtK2AA	502	t	t	2014-10-24 15:47:43.050362
507	30	Geradores elétricos	http://bit.ly/1rxzE76	502	t	t	2014-10-24 15:47:43.05075
508	30	Receptores	http://bit.ly/1rxzKMf	502	t	t	2014-10-24 15:47:43.05121
509	30	Circuitos elétricos	http://bit.ly/1m9o50i	502	t	t	2014-10-24 15:47:43.051602
510	30	Eletromagnetismo	\N	462	t	t	2014-10-24 15:47:43.05211
511	30	Campo magnético	http://bit.ly/1m9o50i	510	t	t	2014-10-24 15:47:43.052529
512	30	Fenômenos magnéticos	http://bit.ly/1qNNRba	510	t	t	2014-10-24 15:47:43.052915
513	30	Força magnética	\N	510	t	t	2014-10-24 15:47:43.053337
514	30	Carga elétrica	http://bit.ly/1pBQjkJ	510	t	t	2014-10-24 15:47:43.053727
515	30	Condutor retilíneo	http://bit.ly/1pBQjkJ	510	t	t	2014-10-24 15:47:43.054181
516	30	Indução eletromagnética	http://bit.ly/1qNOx0e	510	t	t	2014-10-24 15:47:43.05457
517	30	Aplicações do eletromagnetismo	http://bit.ly/1pBRgJJ	510	t	t	2014-10-24 15:47:43.054955
518	30	ENSINO MÉDIO – 3.ª Série	\N	\N	t	t	2014-10-24 15:47:43.055404
519	30	Matéria e radiação:	\N	518	t	t	2014-10-24 15:47:43.055781
520	30	Estrutura do átomo	http://bit.ly/1rxBiG3	519	t	t	2014-10-24 15:47:43.056305
521	30	Descoberta da radioatividade	http://bit.ly/1xtLh2E	519	t	t	2014-10-24 15:47:43.056694
522	30	Isótopos	http://bit.ly/1qNQ0n1	519	t	t	2014-10-24 15:47:43.057167
523	30	Radiações	\N	519	t	t	2014-10-24 15:47:43.057558
524	30	corpusculares	\N	523	t	t	2014-10-24 15:47:43.057941
525	30	ondas eletromagnéticas	http://bit.ly/1rxybxP	523	t	t	2014-10-24 15:47:43.058348
526	30	Produção dos raios X	\N	519	t	t	2014-10-24 15:47:43.058729
527	30	Energia nuclear e suas aplicações	http://bit.ly/1qNRlKJ	519	t	t	2014-10-24 15:47:43.059174
528	30	Universo, terra e vida:	\N	518	t	t	2014-10-24 15:47:43.059566
529	30	O universo	http://bit.ly/1qD2p22	528	t	t	2014-10-24 15:47:43.059952
530	30	Teorias cosmológicas	http://bit.ly/1pBUUDu	528	t	t	2014-10-24 15:47:43.060354
531	30	Sistema solar	http://bit.ly/1xtMmHN	528	t	t	2014-10-24 15:47:43.060752
532	30	Física Moderna e Quântica	\N	518	t	t	2014-10-24 15:47:43.061185
533	30	Conceitos Fundamentais	http://bit.ly/1oX3mxJ	532	t	t	2014-10-24 15:47:43.061577
534	30	Princípio da incerteza	http://bit.ly/1qNVOgs	532	t	t	2014-10-24 15:47:43.061958
535	30	Interpretações da Mecânica Quântica	http://bit.ly/1rxFDsz	532	t	t	2014-10-24 15:47:43.062341
536	30	Epistemologia e História	http://www.fisica.net/mecanica-quantica/Epistemologia-e-Historia-da-Mecanica-Quantica.php	532	t	t	2014-10-24 15:47:43.062726
537	30	Efeito Fotoelétrico	http://bit.ly/1xtMvLb	532	t	t	2014-10-24 15:47:43.063175
538	30	Teoria da relatividade restrita	\N	532	t	t	2014-10-24 15:47:43.063563
539	30	Teoria da relatividade geral	http://bit.ly/1qNVqi1	532	t	t	2014-10-24 15:47:43.063947
958	32	Artes Visuais	http://bit.ly/1eWy4Cb	1959	t	t	2014-10-24 15:47:43.064346
959	32	Arte Rupestre	\N	958	t	t	2014-10-24 15:47:43.064735
960	32	Arte no Antigo Egito	http://bit.ly/1vV9fTT	958	t	t	2014-10-24 15:47:43.065166
961	32	Arte: Greco- Romana	http://bit.ly/1o0VQq4	958	t	t	2014-10-24 15:47:43.065559
962	32	Arte Oriental	\N	958	t	t	2014-10-24 15:47:43.065953
963	32	Africana	http://bit.ly/1EP5GBn	958	t	t	2014-10-24 15:47:43.066354
964	32	Medieval	\N	958	t	t	2014-10-24 15:47:43.066745
965	32	Bizantina	\N	958	t	t	2014-10-24 15:47:43.067177
966	32	Românica	\N	958	t	t	2014-10-24 15:47:43.067569
967	32	Gótica	\N	958	t	t	2014-10-24 15:47:43.067957
968	32	Renascimento	\N	958	t	t	2014-10-24 15:47:43.068355
969	32	Barroco	http://bit.ly/1vXr3LI	958	t	t	2014-10-24 15:47:43.068737
970	32	Neoclassicismo	\N	958	t	t	2014-10-24 15:47:43.06918
971	32	Romantismo	http://bit.ly/1vXr3LI	958	t	t	2014-10-24 15:47:43.069535
972	32	Realismo	\N	958	t	t	2014-10-24 15:47:43.069925
973	32	Impressionismo	\N	958	t	t	2014-10-24 15:47:43.07033
975	32	Fauvismo	\N	958	t	t	2014-10-24 15:47:43.071177
976	32	Cubismo	\N	958	t	t	2014-10-24 15:47:43.071527
977	32	Abstracionismo	\N	958	t	t	2014-10-24 15:47:43.071913
978	32	Dadaísmo	\N	958	t	t	2014-10-24 15:47:43.072334
979	32	Construtivismo Surrealismo	\N	958	t	t	2014-10-24 15:47:43.072713
980	32	 Vanguardas Artísticas	http://bit.ly/1jDQcUy	958	t	t	2014-10-24 15:47:43.073167
981	32	Arte Indígena	http://bit.ly/1vXwhXL	958	t	t	2014-10-24 15:47:43.07353
982	32	Arte Brasileira	\N	958	t	t	2014-10-24 15:47:43.07392
983	32	Arte baiana	http://bit.ly/1gIlGJU	958	t	t	2014-10-24 15:47:43.07432
984	32	Indústria Cultural	\N	958	t	t	2014-10-24 15:47:43.074707
985	32	Arte Contemporânea e Grafite	http://bit.ly/ZoYWKy	958	t	t	2014-10-24 15:47:43.075164
986	32	Arte Moderna	http://bit.ly/1EOCdaC	958	t	t	2014-10-24 15:47:43.075518
987	32	Ave	http://bit.ly/1kWOzqc	958	t	t	2014-10-24 15:47:43.07589
988	32	Catálogo do AVE	http://bit.ly/1gdpeFw	958	t	t	2014-10-24 15:47:43.076309
989	32	Dança	http://bit.ly/PUUONB	1959	t	t	2014-10-24 15:47:43.076676
990	32	Arte Oriental	\N	989	t	t	2014-10-24 15:47:43.077166
991	32	Dança Africana	http://bit.ly/1h2E3pu	989	t	t	2014-10-24 15:47:43.077561
992	32	Dança Africana no Brasil	http://bit.ly/1h2E3pu	991	t	t	2014-10-24 15:47:43.077946
993	32	Renascimento	\N	989	t	t	2014-10-24 15:47:43.07834
994	32	Barroco	http://bit.ly/1vXr3LI	989	t	t	2014-10-24 15:47:43.078726
995	32	Romantismo	\N	989	t	t	2014-10-24 15:47:43.079168
996	32	Expressionismo	\N	989	t	t	2014-10-24 15:47:43.079532
997	32	Vanguardas Artísticas	http://bit.ly/1vVaYsv	989	t	t	2014-10-24 15:47:43.079905
998	32	Dança Popular	\N	989	t	t	2014-10-24 15:47:43.080336
999	32	Dança Indígena	http://bit.ly/PUPv0v	989	t	t	2014-10-24 15:47:43.080702
1000	32	Dança de rua	\N	989	t	t	2014-10-24 15:47:43.081172
1001	32	Indústria Cultural	\N	989	t	t	2014-10-24 15:47:43.081523
1002	32	Dança Clássica	http://bit.ly/PTr4Rf	989	t	t	2014-10-24 15:47:43.081898
1003	32	Dança Moderna	\N	989	t	t	2014-10-24 15:47:43.082298
1004	32	Dança Contemporânea	\N	989	t	t	2014-10-24 15:47:43.082668
1005	32	Hip Hop	http://bit.ly/1vXuSRa	989	t	t	2014-10-24 15:47:43.083156
1006	32	Danças locais	\N	989	t	t	2014-10-24 15:47:43.083521
1007	32	Samba	\N	989	t	t	2014-10-24 15:47:43.083909
1008	32	Capoeira	http://bit.ly/1inhOOh	989	t	t	2014-10-24 15:47:43.08433
1009	32	Maculelê	http://bit.ly/Px7ZTX	989	t	t	2014-10-24 15:47:43.084706
1010	32	Música	http://bit.ly/1eWxTXt	1959	t	t	2014-10-24 15:47:43.085175
1011	32	Arte Oriental	http://bit.ly/1vV8jiz	1010	t	t	2014-10-24 15:47:43.085552
1012	32	Arte Africana	http://bit.ly/1nFbl70	1010	t	t	2014-10-24 15:47:43.08594
1013	32	Arte Medieval	\N	1010	t	t	2014-10-24 15:47:43.086348
1014	32	Renascimento	http://bit.ly/1eWlfYC	1010	t	t	2014-10-24 15:47:43.086742
1015	32	Repente	http://bit.ly/1mP2qLZ	1010	t	t	2014-10-24 15:47:43.087183
1016	32	Rap	\N	1010	t	t	2014-10-24 15:47:43.087576
1017	32	Tecno	\N	1010	t	t	2014-10-24 15:47:43.087913
1018	32	Barroco	http://bit.ly/1vXr3LI	1010	t	t	2014-10-24 15:47:43.088288
1019	32	Classicismo	\N	1010	t	t	2014-10-24 15:47:43.088629
1020	32	Romantismo	\N	1010	t	t	2014-10-24 15:47:43.089065
1021	32	Vanguardas Artísticas	http://bit.ly/1vVaYsv	1010	t	t	2014-10-24 15:47:43.089446
1022	32	Música Eletrônica	\N	1010	t	t	2014-10-24 15:47:43.089798
1023	32	Música Minimalista	\N	1010	t	t	2014-10-24 15:47:43.090246
1024	32	Música Popular Brasileira	http://bit.ly/1gBvdOy	1010	t	t	2014-10-24 15:47:43.090601
1025	32	Arte Indígena	\N	1010	t	t	2014-10-24 15:47:43.090956
1026	32	Indústria Cultural	\N	1010	t	t	2014-10-24 15:47:43.091335
1027	32	Word Music	\N	1010	t	t	2014-10-24 15:47:43.091697
1028	32	Samba	\N	1010	t	t	2014-10-24 15:47:43.092096
1029	32	Sambistas da Bahia	http://bit.ly/1pq7WGh	1028	t	t	2014-10-24 15:47:43.092454
1030	32	Samba – Tia Ciata	http://bit.ly/1d5n7Tp	1028	t	t	2014-10-24 15:47:43.092806
1031	32	Samba – Assis Valente	http://bit.ly/1eX6MLR	1028	t	t	2014-10-24 15:47:43.093256
1032	32	Samba – Bezerra da Silva	http://bit.ly/PTAlbV	1028	t	t	2014-10-24 15:47:43.09363
1033	32	FACE - Festival Anual da Canção Estudantil	http://bit.ly/1uBwn9h	1010	t	t	2014-10-24 15:47:43.09412
1034	32	Musica e Poesia	http://bit.ly/1gA6z8a	1010	t	t	2014-10-24 15:47:43.094477
1035	32	Teatro	http://bit.ly/PUUzSG	1959	t	t	2014-10-24 15:47:43.094828
1036	32	Arte Greco-Romana	http://bit.ly/1o0VQq4	1035	t	t	2014-10-24 15:47:43.095253
1037	32	Oriental	http://bit.ly/1vV8jiz	1035	t	t	2014-10-24 15:47:43.095609
1038	32	Africana	\N	1035	t	t	2014-10-24 15:47:43.095964
1039	32	Medieval	\N	1035	t	t	2014-10-24 15:47:43.096357
1040	32	Barroco	http://bit.ly/1vXr3LI	1035	t	t	2014-10-24 15:47:43.09671
1041	32	Romantismo	\N	1035	t	t	2014-10-24 15:47:43.097156
1042	32	Realismo	http://bit.ly/1vXrJkj	1035	t	t	2014-10-24 15:47:43.09759
1043	32	Vanguardas Artísticas	http://bit.ly/1vVaYsv	1035	t	t	2014-10-24 15:47:43.097959
1044	32	Teatro Dialético	\N	1035	t	t	2014-10-24 15:47:43.098346
1045	32	Teatro do Oprimido	\N	1035	t	t	2014-10-24 15:47:43.098708
1046	32	Teatro Pobre	\N	1035	t	t	2014-10-24 15:47:43.099134
1047	32	Teatro Essencial	\N	1035	t	t	2014-10-24 15:47:43.099489
1048	32	Teatro do Absurdo	http://bit.ly/1dlYpOU	1035	t	t	2014-10-24 15:47:43.099845
1049	32	Arte Engajada	\N	1035	t	t	2014-10-24 15:47:43.100266
1050	32	Arte Popular	\N	1035	t	t	2014-10-24 15:47:43.100625
1051	32	Arte Indígena	\N	1035	t	t	2014-10-24 15:47:43.101034
1052	32	Arte Brasileira	\N	1035	t	t	2014-10-24 15:47:43.101411
1053	32	Teatro baiano	http://bit.ly/ODbs39	1035	t	t	2014-10-24 15:47:43.101761
1673	33	ENSINO MÉDIO – 1.ª Série	\N	\N	t	t	2014-10-24 15:47:43.102265
1674	33	Estudo das origens	\N	1673	t	t	2014-10-24 15:47:43.10261
1675	33	O que é Biologia	http://bit.ly/1sPCdE9	1674	t	t	2014-10-24 15:47:43.102972
1676	33	A Biologia no contexto histórico	http://bit.ly/1sPCdE9	1674	t	t	2014-10-24 15:47:43.10336
1677	33	A Biologia e suas áreas de atuação	http://bit.ly/1utQSVo	1674	t	t	2014-10-24 15:47:43.103713
1678	33	Métodos científicos utilizados em pesquisa nas áreas da Biologia na Bahia, no Brasil e no mundo	http://bit.ly/1rpBmYc	1674	t	t	2014-10-24 15:47:43.10412
1679	33	Origem do universo, da terra e da vida (hipóteses)	http://bit.ly/1rqoEbC	1674	t	t	2014-10-24 15:47:43.104485
1680	33	Estudo das substâncias	\N	1674	t	t	2014-10-24 15:47:43.104868
1681	33	Substâncias orgânicas e inorgânicas	http://bit.ly/1pwH05p	1681	t	t	2014-10-24 15:47:43.105266
1682	33	Alimentos adequados à prevenção de doenças	http://bit.ly/1rqtmpW	1681	t	t	2014-10-24 15:47:43.105623
1683	33	Fome no mundo (desigualdade e distribuição de renda)	http://bit.ly/1tp7lEX	1681	t	t	2014-10-24 15:47:43.106029
1684	33	Principais doenças causadas pela alimentação inadequada (regional e nacional)	http://bit.ly/1s0N2CX	1681	t	t	2014-10-24 15:47:43.106407
1685	33	Alimentos ricos em aminoácidos	http://bit.ly/1vKzutA	1681	t	t	2014-10-24 15:47:43.106795
1686	33	Organização celular da vida	\N	1673	t	t	2014-10-24 15:47:43.107238
1687	33	A organização celular na estrutura de diferentes seres vivos como característica fundamental de diferentes formas vivas	http://bit.ly/1vKzV7b	1686	t	t	2014-10-24 15:47:43.107593
1688	33	 Organização e funcionamento de diferentes tipos de células	http://bit.ly/1vKzV7b	1686	t	t	2014-10-24 15:47:43.108136
1689	33	 Representação de diferentes tipos de células	http://bit.ly/1vKzV7b	1686	t	t	2014-10-24 15:47:43.108498
1690	33	Obtenção de energia pelos sistemas vivos:	http://bit.ly/1vKAT3v	1686	t	t	2014-10-24 15:47:43.108899
1691	33	Fotossíntese	http://bit.ly/1vKAT3v	1686	t	t	2014-10-24 15:47:43.1094
1692	33	Respiração celular	http://bit.ly/1s9cRPM	1686	t	t	2014-10-24 15:47:43.109808
1693	33	Código genético	\N	1616	t	t	2014-10-24 15:47:43.11027
1694	33	Tipos de ácidos nucléicos	http://bit.ly/1vKCGp8	1693	t	t	2014-10-24 15:47:43.110694
1695	33	Organelas celulares e o mecanismo de síntese de proteínas específicas	http://bit.ly/1s9ebCf	1693	t	t	2014-10-24 15:47:43.111106
1696	33	Relação entre DNA e seu código genético	http://bit.ly/1Ae32Su	1693	t	t	2014-10-24 15:47:43.11151
1697	33	Embriologia animal	\N	1616	t	t	2014-10-24 15:47:43.111954
1698	33	Fecundação:	http://bit.ly/1r15oNJ	1697	t	t	2014-10-24 15:47:43.112404
1699	33	segmentação	http://bit.ly/1r15oNJ	1698	t	t	2014-10-24 15:47:43.112856
1700	33	formação do embrião	http://bit.ly/1r15oNJ	1698	t	t	2014-10-24 15:47:43.113305
1701	33	fases de desenvolvimento embrionário	http://bit.ly/1r15oNJ	1698	t	t	2014-10-24 15:47:43.113721
1702	33	Gravidez na adolescência como forma de risco à saúde e gravidez indesejada	http://bit.ly/1wiZ7RT	1698	t	t	2014-10-24 15:47:43.114148
1703	33	Tecidos Humanos:	\N	1616	t	t	2014-10-24 15:47:43.114566
1704	33	Tecidos epiteliais	http://bit.ly/1vKFEdl	1703	t	t	2014-10-24 15:47:43.114982
1705	33	Tecidos conjuntivos	http://bit.ly/1s9m5eQ	1703	t	t	2014-10-24 15:47:43.115453
1706	33	Tecidos musculares	\N	1703	t	t	2014-10-24 15:47:43.115866
1707	33	Tecido nervoso	\N	1703	t	t	2014-10-24 15:47:43.116335
1708	33	Doenças originadas por exposição à luz solar, radiação, tais como câncer de pele e envelhecimento precoce	\N	1703	t	t	2014-10-24 15:47:43.116742
1709	33	Doenças dermatológicas causadas por falta de higiene como escabiose e micoses	\N	1703	t	t	2014-10-24 15:47:43.117186
1710	33	Importância do exercício físico para a saúde	http://bit.ly/1s9pYR0	1703	t	t	2014-10-24 15:47:43.117604
1711	33	Distribuição desigual de saúde pelas populações	\N	1616	t	t	2014-10-24 15:47:43.118078
1712	33	Condições socioeconômicas e qualidade de vida das populações humanas de diferentes regiões brasileiras	http://bit.ly/1s9Ied0	1711	t	t	2014-10-24 15:47:43.118503
1713	33	Principais indicadores de desenvolvimento humano e de saúde pública:	http://bit.ly/1rJZtki	1711	t	t	2014-10-24 15:47:43.118908
1714	33	Mortalidade infantil	http://bit.ly/1s9N2z9	1711	t	t	2014-10-24 15:47:43.119355
1715	33	Expectativa de vida	http://bit.ly/1s9N2z9	1711	t	t	2014-10-24 15:47:43.11977
1716	33	Mortalidade	http://bit.ly/1s9N2z9	1711	t	t	2014-10-24 15:47:43.120191
1717	33	Doenças infectocontagiosas	http://bit.ly/1s9NZY5	1711	t	t	2014-10-24 15:47:43.12061
1718	33	Condições de saneamento	http://bit.ly/1s9Oql4	1711	t	t	2014-10-24 15:47:43.12108
1719	33	Condições de moradia	\N	1711	t	t	2014-10-24 15:47:43.121496
1720	33	Acesso aos serviços de saúde	http://bit.ly/1s9Ied0	1711	t	t	2014-10-24 15:47:43.121918
1721	33	Acesso aos serviços educacionais	http://bit.ly/1o3m8ZC	1711	t	t	2014-10-24 15:47:43.122361
1722	33	ENSINO MÉDIO – 2.ª Série	\N	\N	t	t	2014-10-24 15:47:43.122767
1723	33	Diversidade animal	\N	1722	t	t	2014-10-24 15:47:43.123279
1724	33	Classificação biológica para organização e compreensão da grande diversidade dos seres vivos	http://bit.ly/ZyvJfx	1723	t	t	2014-10-24 15:47:43.123702
1725	33	Critérios de classificação, regras de nomenclatura e categorias taxonômicas atuais	http://bit.ly/ZyvJfx	1723	t	t	2014-10-24 15:47:43.124152
1726	33	Principais características de representantes de cada um dos seis reinos, identificando especificidades relacionadas às condições ambientais	http://bit.ly/ZyvJfx	1723	t	t	2014-10-24 15:47:43.124575
1727	33	Relações de perentesco entre diversos seres vivos	http://bit.ly/ZywipB	1723	t	t	2014-10-24 15:47:43.125075
1728	33	Seres vivos	\N	1722	t	t	2014-10-24 15:47:43.125518
1729	33	Funções vitais dos animais e plantas	http://bit.ly/1subBqA	1728	t	t	2014-10-24 15:47:43.125941
1730	33	Caracterização dos ciclos de vidas de animais e vegetais	http://bit.ly/1s2FSfq	1728	t	t	2014-10-24 15:47:43.126372
1731	33	Funções vitais do organismo humano	http://bit.ly/ZywAwK	1728	t	t	2014-10-24 15:47:43.126781
1732	33	Diversidade ameaçada	\N	1722	t	t	2014-10-24 15:47:43.127638
1733	33	Diversidade das espécies do planeta e as condições climáticas	http://bit.ly/1sIEbDG	1732	t	t	2014-10-24 15:47:43.128154
1734	33	Fauna e flora dos biomas terrestres, especialmente brasileiros	http://bit.ly/1tl2Vi9	1732	t	t	2014-10-24 15:47:43.128582
1735	33	Principais ecossistemas brasileiros e os que se encontram ameaçados e as principais causas de destruição	http://bit.ly/1tl2Vi9	1732	t	t	2014-10-24 15:47:43.129074
1736	33	Questão ambiental no mundo – G8, Agenda 21, Protocolo de Kyotol	http://bit.ly/1ozKAPX	1732	t	t	2014-10-24 15:47:43.129553
1737	33	Uso sustentável da biodiversidade	http://bit.ly/Zyx71z	1732	t	t	2014-10-24 15:47:43.130111
1738	33	Diversidade das plantas	\N	1722	t	t	2014-10-24 15:47:43.130581
1739	33	Evolução das plantas	http://bit.ly/Zyx85R	1738	t	t	2014-10-24 15:47:43.13115
1740	33	árvores filogenéticas	hhttp://bit.ly/1w949Dm	1738	t	t	2014-10-24 15:47:43.131646
1741	33	as plantas na medicina	http://bit.ly/ZyxgSS	1738	t	t	2014-10-24 15:47:43.132152
1742	33	na indústria de cosméticos e ornamental	\N	1738	t	t	2014-10-24 15:47:43.132618
1743	33	Saúde da população	http://bit.ly/1oBFnm9	1722	t	t	2014-10-24 15:47:43.133116
1744	33	Tipos de doenças:	http://bit.ly/1snYW7B	1743	t	t	2014-10-24 15:47:43.133581
1745	33	infectocontagiosas	http://bit.ly/1ogI0uW	1744	t	t	2014-10-24 15:47:43.134165
1746	33	parasitárias	http://bit.ly/1vKRJiG	1744	t	t	2014-10-24 15:47:43.134636
1747	33	degenerativas	http://bit.ly/1utUBCg	1744	t	t	2014-10-24 15:47:43.135154
1748	33	ocupacionais	http://bit.ly/1sugRuf	1744	t	t	2014-10-24 15:47:43.135622
1749	33	sexualmente transmissíveis	http://bit.ly/Zyxrxs	1744	t	t	2014-10-24 15:47:43.136159
1750	33	provocadas por toxinas ambientais	\N	1744	t	t	2014-10-24 15:47:43.136635
1751	33	Tecnologia na saúde:	http://bit.ly/ZyxVnf	1743	t	t	2014-10-24 15:47:43.137164
1752	33	vacina	http://bit.ly/1sc7Wxi	1751	t	t	2014-10-24 15:47:43.137634
1753	33	medicamentos	http://bit.ly/Zyy1v4	1751	t	t	2014-10-24 15:47:43.138129
1754	33	exames diagnósticos	\N	1751	t	t	2014-10-24 15:47:43.138596
1755	33	alimentos enriquecidos	http://bit.ly/1toMYaI	1751	t	t	2014-10-24 15:47:43.139151
1756	33	uso de adoçantes	http://bit.ly/1toMYaI	1751	t	t	2014-10-24 15:47:43.13962
1757	33	Saúde Ambiental	\N	1722	t	t	2014-10-24 15:47:43.140159
1758	33	Fontes poluidoras	http://bit.ly/1tpdJfe	1757	t	t	2014-10-24 15:47:43.140627
1759	33	Medidas individuais e coletivas e do poder público para minimizar os efeitos das interferências humanas	http://bit.ly/1tpdJfe	1757	t	t	2014-10-24 15:47:43.141145
1343	35	Atividades físicas	\N	1337	t	t	2014-10-24 15:47:43.505335
1344	35	Estrangeirismos	http://bit.ly/1fL53J8	1332	t	t	2014-10-24 15:47:43.505667
1760	33	Contradições entre conservação ambiental, uso econômico da biodiversidade, expansão das fronteiras agrícolas e extrativistas	http://bit.ly/1x07B0L	1757	t	t	2014-10-24 15:47:43.141672
1761	33	Tecnologias ambientais para a sustentabilidade ambiental	http://bit.ly/Zyya1E	1757	t	t	2014-10-24 15:47:43.14218
1762	33	Conferências internacionais, compromissos e propostas para recuperação dos ambientes brasileiros	http://bit.ly/1uS4Tft	1757	t	t	2014-10-24 15:47:43.142645
1763	33	ENSINO MÉDIO – 3.ª Série	\N	\N	t	t	2014-10-24 15:47:43.143161
1764	33	Origem e evolução dos seres vivos	\N	1763	t	t	2014-10-24 15:47:43.143631
1765	33	Evolucionismo e Evolução biológica	http://bit.ly/1st1AKk	1764	t	t	2014-10-24 15:47:43.144187
1766	33	 Fundamentos da Hereditariedade	\N	1763	t	t	2014-10-24 15:47:43.14466
1767	33	Características hereditárias, congênitas e adquiridas	http://bit.ly/1Ae32Su	1766	t	t	2014-10-24 15:47:43.145193
1768	33	Hereditariedade: concepções premendelianas as leis de Mendel	http://bit.ly/1Ae32Su	1766	t	t	2014-10-24 15:47:43.145663
1769	33	Teoria cromossômica da herança: determinação do sexo e herança ligada ao sexo	http://bit.ly/1Ae32Su	1766	t	t	2014-10-24 15:47:43.146197
1770	33	Cariótipo normal e aberrações cromossômicas mais comuns (sindromes de Down, Turner e Klinefelter)	http://bit.ly/1Ae32Su	1766	t	t	2014-10-24 15:47:43.146676
1771	33	Estrutura de moléculas de DNA	http://bit.ly/1Ae32Su	1766	t	t	2014-10-24 15:47:43.147185
1772	33	Estrutura de moléculas de RNA	http://bit.ly/1o3qjVB	1766	t	t	2014-10-24 15:47:43.147652
1773	33	Idéias evolucionistas e evolução biológica	\N	1763	t	t	2014-10-24 15:47:43.148338
1774	33	Idéias evolucionistas de Darwin e Lamarck	http://bit.ly/1Ae32Su	1773	t	t	2014-10-24 15:47:43.148845
1775	33	Mecanismos da evolução das espécies: mutação, recombinação Gênica e seleção natural	http://bit.ly/1st1AKk	1773	t	t	2014-10-24 15:47:43.149367
1776	33	Fatores que interferem na constituição genética das populações, migrações, mutações, seleção e deriva genética	http://bit.ly/1s1Gp1k	1773	t	t	2014-10-24 15:47:43.149768
1777	33	Linhas de evolução de seres vivos	http://bit.ly/1s1Gp1k	1773	t	t	2014-10-24 15:47:43.150346
1778	33	Genética humana e saúde	\N	1763	t	t	2014-10-24 15:47:43.150756
1779	33	Grupos sanguíneos, transfusões e incompatibilidade	http://bit.ly/1C1kxV3	1778	t	t	2014-10-24 15:47:43.151319
1780	33	Tecnologias na prevenção de doenças metabólicas	\N	1778	t	t	2014-10-24 15:47:43.151707
1781	33	Transplantes e doenças auto-imunes	\N	1778	t	t	2014-10-24 15:47:43.152176
1782	33	Estrutura quimica e duplicação do DNA	http://bit.ly/1Ae32Su	1778	t	t	2014-10-24 15:47:43.152586
1783	33	Engenharia genética e produtos geneticamente modificados: alimentos, produtos farmaceuticos, hormônios, vacinas e medicamentos	http://bit.ly/1o3zPIj	1778	t	t	2014-10-24 15:47:43.152977
1784	33	Ecologia	\N	1763	t	t	2014-10-24 15:47:43.153405
1785	33	Ecossistemas	http://bit.ly/Zys1Cz	1784	t	t	2014-10-24 15:47:43.153801
1786	33	Teia alimentar, sucessão e comunidade clímax	http://bit.ly/Zysgxy	1784	t	t	2014-10-24 15:47:43.154277
1787	33	Problemas ambientais:	http://bit.ly/1tpdJfe	1784	t	t	2014-10-24 15:47:43.154668
1788	33	Mudanças climáticas, efeito estufa	http://bit.ly/1stTQrp	1784	t	t	2014-10-24 15:47:43.155173
1789	33	Desmatamento	http://bit.ly/ZysLI1	1784	t	t	2014-10-24 15:47:43.15557
1790	33	Erosão	http://bit.ly/1C1Yttn	1784	t	t	2014-10-24 15:47:43.155951
1791	33	Poluição da água	http://bit.ly/ZysODB	1784	t	t	2014-10-24 15:47:43.156383
1792	33	Poluição do solo	http://bit.ly/1o3LxlW	1784	t	t	2014-10-24 15:47:43.156786
1793	33	Poluição do ar	http://bit.ly/1o3LF4U	1784	t	t	2014-10-24 15:47:43.157262
1794	33	Densidade e crescimento da população	http://bit.ly/1rJZtki	1784	t	t	2014-10-24 15:47:43.157651
541	34	A origem da filosofia: fatores históricos e contribuições de outros povos.	\N	540	t	t	2014-10-24 15:47:43.158067
542	34	Origem da filosofia	http://bit.ly/PTmv9x	541	t	t	2014-10-24 15:47:43.158439
543	34	Significado da filosofia	http://bit.ly/1peFWDP	541	t	t	2014-10-24 15:47:43.158836
544	34	Características da filosofia: do senso comum ao senso crítico	\N	540	t	t	2014-10-24 15:47:43.159351
545	34	Senso comum	http://bit.ly/1eVjbQz	544	t	t	2014-10-24 15:47:43.159719
546	34	Pensamento mitológico	\N	540	t	t	2014-10-24 15:47:43.160092
547	34	Pensamento mítico	http://bit.ly/1eVjDy7	546	t	t	2014-10-24 15:47:43.160458
549	34	Mito	http://bit.ly/1peIXE2	548	t	t	2014-10-24 15:47:43.160819
550	34	Mitologia	http://bit.ly/1peIXE2	548	t	t	2014-10-24 15:47:43.161227
551	34	O mito nas civilizações antigas e hoje	http://bit.ly/1tkvKeN	552	t	t	2014-10-24 15:47:43.161595
553	34	Dogmatismo	http://bit.ly/1peJKou	552	t	t	2014-10-24 15:47:43.161959
554	34	Ceticismo	http://bit.ly/1peJRQU	552	t	t	2014-10-24 15:47:43.162335
555	34	Relativismo	http://bit.ly/1peJXbu	552	t	t	2014-10-24 15:47:43.1627
556	34	A pluralidade do conhecimento: o legado dos gregos	http://bit.ly/1eXibLC	552	t	t	2014-10-24 15:47:43.163106
557	34	Os primeiros filósofos – a natureza como objeto de estudo	http://bit.ly/1sPeggd	552	t	t	2014-10-24 15:47:43.163474
558	34	Conhece-te a ti mesmo: um exercício socrático	http://bit.ly/1gIb6ml	552	t	t	2014-10-24 15:47:43.163842
559	34	Ágora: espaço da argumentação e da construção ética	\N	552	t	t	2014-10-24 15:47:43.164218
560	34	Os filósofos da natureza e a necessidade de investigação	http://bit.ly/1d3vzCC	552	t	t	2014-10-24 15:47:43.164584
561	34	A retórica e a utilização do discurso como instrumento de inserção social.	\N	552	t	t	2014-10-24 15:47:43.164947
562	34	Democracia e cidadania na polis grega: princípios, características e exclusões	\N	540	t	t	2014-10-24 15:47:43.165325
563	34	Democracia grega	http://bit.ly/1pczEog	562	t	t	2014-10-24 15:47:43.165699
564	34	Grécia	http://bit.ly/1peLNZO	562	t	t	2014-10-24 15:47:43.166071
565	34	Paidéia	http://bit.ly/PTuQKh	562	t	t	2014-10-24 15:47:43.166439
566	34	Formação do cidadão	http://bit.ly/PTv2cz	562	t	t	2014-10-24 15:47:43.166809
567	34	Cidadania grega	http://bit.ly/PTvcRf	562	t	t	2014-10-24 15:47:43.167191
568	34	Conhecimento e virtude em Sócrates	http://bit.ly/1tkxF2P	562	t	t	2014-10-24 15:47:43.167557
569	34	ENSINO MÉDIO – 2.ª Série	\N	\N	t	t	2014-10-24 15:47:43.167924
570	34	Tipos de conhecimentos e suas especificidades:	\N	569	t	t	2014-10-24 15:47:43.1683
571	34	Conhecimento	http://bit.ly/PTvOq3	570	t	t	2014-10-24 15:47:43.168665
572	34	Conhecimento científico	http://bit.ly/1pczEog	570	t	t	2014-10-24 15:47:43.169136
573	34	Senso comum	http://bit.ly/1pczEog	570	t	t	2014-10-24 15:47:43.169753
574	34	Conhecimento filosófico	http://bit.ly/PTxDD7	570	t	t	2014-10-24 15:47:43.170279
575	34	Conhecimento religioso	http://bit.ly/1peOGtB	570	t	t	2014-10-24 15:47:43.170816
576	34	O pensamento como patrimônio da humanidade	http://bit.ly/1tkybhe	570	t	t	2014-10-24 15:47:43.171342
577	34	Origem, desenvolvimento e crise da metafísica	http://bit.ly/1sPfNmw	570	t	t	2014-10-24 15:47:43.17185
578	34	A busca, as concepções e a teorias sobre a verdade	http://bit.ly/1pePv5Q	570	t	t	2014-10-24 15:47:43.172364
1345	35	Estruturas gramaticais:	\N	1332	t	t	2014-10-24 15:47:43.506096
579	34	O que é lógica? Conhecimento e lógica. Lógica Aristotélica. Princípios da lógica e Argumentação tipos e falácias.	\N	569	t	t	2014-10-24 15:47:43.172936
580	34	Lógica	http://bit.ly/1d3BgAB	579	t	t	2014-10-24 15:47:43.173478
581	34	Lógica Aristotélica	http://bit.ly/1d3BgAB	579	t	t	2014-10-24 15:47:43.174038
582	34	Aristóteles	http://bit.ly/1pczEog	579	t	t	2014-10-24 15:47:43.174542
583	34	Conhecimento	http://bit.ly/1peMP8i	579	t	t	2014-10-24 15:47:43.17509
584	34	Argumentação	http://bit.ly/1pczEog	579	t	t	2014-10-24 15:47:43.175713
585	34	Linguagem e Argumentação. Lógica e Pensamento. Filosofia da Linguagem.	\N	569	t	t	2014-10-24 15:47:43.176324
586	34	Filosofia da linguagem	http://bit.ly/1peTIGG	585	t	t	2014-10-24 15:47:43.176858
587	34	Linguagem	http://bit.ly/1pczEog	585	t	t	2014-10-24 15:47:43.177375
588	34	Argumentação	http://bit.ly/1pczEog	585	t	t	2014-10-24 15:47:43.177881
589	34	Pensamento filosófico	http://bit.ly/PTyrrO	585	t	t	2014-10-24 15:47:43.178435
590	34	O pensamento político cristão: a patrística, a escolástica e a colonização brasileira:	\N	569	t	t	2014-10-24 15:47:43.178941
591	34	Pensamento Cristão	http://bit.ly/1peOGtB	590	t	t	2014-10-24 15:47:43.179519
592	34	Filosofia Cristã	http://bit.ly/1pczEog	590	t	t	2014-10-24 15:47:43.180106
593	34	Patrística	http://bit.ly/1gc6yFY	590	t	t	2014-10-24 15:47:43.180614
594	34	Escolástica	http://bit.ly/1eVx0OX	590	t	t	2014-10-24 15:47:43.18113
595	34	Colonização brasileira	http://bit.ly/1pczEog	590	t	t	2014-10-24 15:47:43.181639
596	34	A concepção de Estado Moderno:	\N	569	t	t	2014-10-24 15:47:43.182151
597	34	Soberania	http://bit.ly/1sPgsUU	596	t	t	2014-10-24 15:47:43.182663
598	34	Liberdade	http://bit.ly/1sPgsUU	596	t	t	2014-10-24 15:47:43.183179
599	34	Igualdade	http://bit.ly/1sPgsUU	596	t	t	2014-10-24 15:47:43.183744
600	34	Pluralidade	http://bit.ly/1sPgsUU	596	t	t	2014-10-24 15:47:43.1843
601	34	Ser humano moderno e seu lugar no mundo	http://bit.ly/1sPgsUU	596	t	t	2014-10-24 15:47:43.184802
602	34	Racionalismo e empirismo:	\N	569	t	t	2014-10-24 15:47:43.185323
603	34	Racionalismo	http://bit.ly/1eVxIf7	602	t	t	2014-10-24 15:47:43.18583
604	34	Empirismo	http://bit.ly/1d3Gyfu	602	t	t	2014-10-24 15:47:43.186349
605	34	Da técnica à tecnologia:	\N	569	t	t	2014-10-24 15:47:43.186856
606	34	Técnica	http://bit.ly/1mVy7rr	605	t	t	2014-10-24 15:47:43.187393
607	34	Tecnologia	http://bit.ly/1mVy7rr	605	t	t	2014-10-24 15:47:43.187899
608	34	Modernidade e os postulados do cientificismo	http://bit.ly/1gVWZW4	605	t	t	2014-10-24 15:47:43.188438
609	34	O público e o privado na antiguidade clássica, na modernidade e na atualidade	http://bit.ly/1tkAEZ5	605	t	t	2014-10-24 15:47:43.188949
610	34	ENSINO MÉDIO – 3.ª Série	\N	\N	t	t	2014-10-24 15:47:43.189512
611	34	Apresentação e experiência do mundo real como interpretação humana	http://bit.ly/1tkDU6U	610	t	t	2014-10-24 15:47:43.190133
612	34	O bem, a virtude e a felicidade	http://bit.ly/1x3w8C5	610	t	t	2014-10-24 15:47:43.19064
613	34	Ética e Racionalidade:	\N	610	t	t	2014-10-24 15:47:43.19117
614	34	Ética	http://bit.ly/1d3I0hO	613	t	t	2014-10-24 15:47:43.191681
615	34	Racionalidade	http://bit.ly/1pczEog	613	t	t	2014-10-24 15:47:43.192238
616	34	As principais concepções éticas e morais:	\N	610	t	t	2014-10-24 15:47:43.192743
617	34	Ética	http://bit.ly/PTIXPR	616	t	t	2014-10-24 15:47:43.19326
618	34	Moral	http://bit.ly/1eVzXPz	616	t	t	2014-10-24 15:47:43.19377
619	34	Ética e Meio ambiente:	\N	610	t	t	2014-10-24 15:47:43.1943
620	34	Ética	http://bit.ly/1d3I0hO	619	t	t	2014-10-24 15:47:43.194813
621	34	Meio Ambiente	http://bit.ly/1gWocYN	619	t	t	2014-10-24 15:47:43.19533
622	34	A questão dos Valores: Dever, Liberdade, Responsabilidade	http://bit.ly/1eVzXPz	619	t	t	2014-10-24 15:47:43.195836
623	34	O Problema do valor e agir moral, o bem, a virtude e a felicidade	\N	610	t	t	2014-10-24 15:47:43.196355
624	34	Valores	http://bit.ly/1pczEog	623	t	t	2014-10-24 15:47:43.196865
625	34	Moral	http://bit.ly/1eVzXPz	623	t	t	2014-10-24 15:47:43.197435
626	34	Problemas éticos da atualidade (bioética, clonagem, ecologia, eutanásia, aborto)	http://bit.ly/P8vJ0q	623	t	t	2014-10-24 15:47:43.197969
627	34	Política e teorias do Estado x Política e Ordem Social:	\N	610	t	t	2014-10-24 15:47:43.198483
628	34	Teoria política	http://bit.ly/1eVAQrf	627	t	t	2014-10-24 15:47:43.199097
629	34	Política moderna	http://bit.ly/1peZxE1	627	t	t	2014-10-24 15:47:43.199609
630	34	Os avessos da democracia:	http://bit.ly/1peZxE1	610	t	t	2014-10-24 15:47:43.200132
631	34	Ausência de liberdade	http://bit.ly/1eVBRzI	630	t	t	2014-10-24 15:47:43.200643
632	34	Totalitarismo	http://bit.ly/1eVBwwQ	630	t	t	2014-10-24 15:47:43.201162
633	34	Fundamentalismo	http://bit.ly/1d3K9dx	630	t	t	2014-10-24 15:47:43.201667
634	34	Democracia e cidadania:	\N	610	t	t	2014-10-24 15:47:43.202238
635	34	Democracia	http://bit.ly/1eVYDHI	634	t	t	2014-10-24 15:47:43.202746
636	34	Cidadania	http://bit.ly/1d48mjL	634	t	t	2014-10-24 15:47:43.203304
637	34	Política e Natureza Humana	http://bit.ly/1x3zhSr	634	t	t	2014-10-24 15:47:43.203813
638	34	Justiça e Liberdade: O mito da democracia racial no Brasil:	\N	610	t	t	2014-10-24 15:47:43.204366
639	34	Justiça	http://bit.ly/PUfmFX	638	t	t	2014-10-24 15:47:43.204877
640	34	Liberdade	http://bit.ly/1pfnTxs	638	t	t	2014-10-24 15:47:43.205463
641	34	Direitos humanos	http://bit.ly/1d49Fzj	638	t	t	2014-10-24 15:47:43.206041
642	34	Poder, Estado e Democracia:	\N	610	t	t	2014-10-24 15:47:43.206548
643	34	Poder	http://bit.ly/1d49W52	642	t	t	2014-10-24 15:47:43.207103
644	34	Estado	http://bit.ly/PUgBoz	642	t	t	2014-10-24 15:47:43.207611
645	34	Democracia	http://bit.ly/1pfoRJQ	642	t	t	2014-10-24 15:47:43.208125
646	34	História das ideias políticas	http://bit.ly/1tkHGgt	642	t	t	2014-10-24 15:47:43.208655
647	34	Marx	http://bit.ly/1d4aYxZ	642	t	t	2014-10-24 15:47:43.209204
648	34	O Marxismo	http://bit.ly/1d4ba0p	642	t	t	2014-10-24 15:47:43.209537
649	34	Liberalismo	http://bit.ly/1d4bjkj	642	t	t	2014-10-24 15:47:43.209871
650	34	Socialismo	http://bit.ly/1d4br3d	642	t	t	2014-10-24 15:47:43.210228
651	34	Comunismo	http://bit.ly/1pfpLWI	642	t	t	2014-10-24 15:47:43.210558
652	34	Ideologia	http://bit.ly/1eW2BA4	642	t	t	2014-10-24 15:47:43.210889
653	34	Reflexão atual sobre a política:	\N	610	t	t	2014-10-24 15:47:43.211243
654	34	Globalização	http://bit.ly/PUicLd	653	t	t	2014-10-24 15:47:43.211576
655	34	Neoliberalismo	http://bit.ly/1eW2UL5	653	t	t	2014-10-24 15:47:43.211915
656	34	Política moderna	http://bit.ly/PTKyVR	653	t	t	2014-10-24 15:47:43.212299
657	34	A razão instrumental e o mal estar da modernidade	http://bit.ly/1x3AsBr	653	t	t	2014-10-24 15:47:43.212633
658	34	O materialismo histórico dialético e a crítica à sociedade capitalista:	\N	610	t	t	2014-10-24 15:47:43.213084
659	34	Marx	http://bit.ly/1d4aYxZ	658	t	t	2014-10-24 15:47:43.21343
660	34	Marxismo	http://bit.ly/1d4ba0p	658	t	t	2014-10-24 15:47:43.213764
661	34	Os desafios filosóficos da atualidade	http://bit.ly/1mVIr2L	658	t	t	2014-10-24 15:47:43.21421
662	34	Os discursos filosóficos contemporâneos e uma nova concepção de ciência e filosofia e o valor da existência humana em Nietzsche e Sartre	\N	610	t	t	2014-10-24 15:47:43.214582
663	34	Filosofia contemporânea	http://bit.ly/1mVIr2L	662	t	t	2014-10-24 15:47:43.214914
664	34	Nietzsche	http://bit.ly/1eVvFrc	662	t	t	2014-10-24 15:47:43.215291
665	34	O Homem - Um Ser Consciente	\N	662	t	t	2014-10-24 15:47:43.46507
666	34	Estética: conceito e história do termo estética	http://bit.ly/1dmQ2T8	662	t	t	2014-10-24 15:47:43.465684
667	34	Arte e representação de mundo	http://bit.ly/1sPmYLy	662	t	t	2014-10-24 15:47:43.466208
668	34	Concepções Estéticas: Naturalismo, Classicismo	http://bit.ly/1tkJXbC	662	t	t	2014-10-24 15:47:43.466726
669	34	A criação artística, Teorias do belo, Intuição, imagem, poesia	http://bit.ly/1tkJXbC	662	t	t	2014-10-24 15:47:43.46725
670	34	Concepções estéticas: naturalismo, romantismo, classicismo, vanguarda, pós-modernismo	http://bit.ly/1tkJXbC	662	t	t	2014-10-24 15:47:43.467757
1259	35	ENSINO MÉDIO – 1ª série	\N	\N	t	t	2014-10-24 15:47:43.468302
1260	35	Leitura e compreensão de textos escritos	http://bit.ly/1hCjVQs	1259	t	t	2014-10-24 15:47:43.468798
1261	35	Pronúncia	http://oli.cmu.edu/courses/free-open/speech-course-details/	1259	t	t	2014-10-24 15:47:43.469473
1262	35	Importância do aprendizado da língua inglesa	\N	1259	t	t	2014-10-24 15:47:43.470096
1263	35	Estrangeirismos	http://bit.ly/1fL53J8	1259	t	t	2014-10-24 15:47:43.470618
1264	35	Imperativo	\N	1259	t	t	2014-10-24 15:47:43.471138
1265	35	Verbo To Be (Presente)	http://escoladigital.org.br/curso-de-ingles-conversacao-episodio-2/	1259	t	t	2014-10-24 15:47:43.471656
1266	35	Pronomes pessoais	\N	1259	t	t	2014-10-24 15:47:43.472203
1267	35	Artigos	\N	1259	t	t	2014-10-24 15:47:43.472712
1268	35	Pronomes demonstrativos	\N	1259	t	t	2014-10-24 15:47:43.47323
1269	35	Pronomes interrogativos	\N	1259	t	t	2014-10-24 15:47:43.473753
1270	35	Saudações e cumprimentos	\N	1259	t	t	2014-10-24 15:47:43.4743
1271	35	Vocabulário:	\N	1259	t	t	2014-10-24 15:47:43.474819
1272	35	Cores	\N	1271	t	t	2014-10-24 15:47:43.475337
1273	35	Formas	\N	1271	t	t	2014-10-24 15:47:43.475846
1274	35	Relacionado à tecnologia	\N	1271	t	t	2014-10-24 15:47:43.47638
1275	35	Internet	\N	1274	t	t	2014-10-24 15:47:43.47689
1276	35	Informações pessoais:	\N	1259	t	t	2014-10-24 15:47:43.477471
1277	35	Nome	\N	1276	t	t	2014-10-24 15:47:43.478045
1278	35	Idade	\N	1276	t	t	2014-10-24 15:47:43.478556
1279	35	Cidade de origem	\N	1276	t	t	2014-10-24 15:47:43.479127
1280	35	Técnicas de Leitura:	\N	1259	t	t	2014-10-24 15:47:43.479632
1281	35	Leitura de imagem	http://bit.ly/1zRsZXN	1280	t	t	2014-10-24 15:47:43.480144
1282	35	Análise do título do texto	http://bit.ly/1zRsZXN	1280	t	t	2014-10-24 15:47:43.480652
1283	35	Skimming	http://bit.ly/1zRsZXN	1280	t	t	2014-10-24 15:47:43.481169
1284	35	Scanning	http://bit.ly/1zRsZXN	1280	t	t	2014-10-24 15:47:43.481679
1285	35	Localização da temática principal	http://bit.ly/1hCjVQs	1280	t	t	2014-10-24 15:47:43.482234
1286	35	Inferência de palavras desconhecidas	http://bit.ly/1hCjVQs	1280	t	t	2014-10-24 15:47:43.482746
1287	35	Inferência do significado de palavras	http://bit.ly/1hCjVQs	1280	t	t	2014-10-24 15:47:43.483294
1288	35	Verbos no presente simples	\N	1259	t	t	2014-10-24 15:47:43.483801
1289	35	Advérbios de freqüência	\N	1259	t	t	2014-10-24 15:47:43.484322
1290	35	Caso genitivo	\N	1259	t	t	2014-10-24 15:47:43.484832
1291	35	Substantivos contáveis e incontáveis	\N	1259	t	t	2014-10-24 15:47:43.485346
1292	35	Pronomes indefinidos	\N	1259	t	t	2014-10-24 15:47:43.48585
1293	35	There to be (present)	\N	1259	t	t	2014-10-24 15:47:43.486372
1294	35	Vocabulário:	\N	1259	t	t	2014-10-24 15:47:43.486875
1295	35	Membros da família	\N	1294	t	t	2014-10-24 15:47:43.487399
1296	35	Lugares	\N	1294	t	t	2014-10-24 15:47:43.487902
1297	35	Dias da semana	\N	1294	t	t	2014-10-24 15:47:43.488438
1298	35	Meses do ano	\N	1294	t	t	2014-10-24 15:47:43.488995
1299	35	Estações do ano	\N	1294	t	t	2014-10-24 15:47:43.489386
1300	35	Frutas e vegetais	\N	1294	t	t	2014-10-24 15:47:43.489722
1301	35	ENSINO MÉDIO – 2ª série	\N	\N	t	t	2014-10-24 15:47:43.490136
1302	35	Biografia de músicos famosos	http://bit.ly/1hCjVQs	1301	t	t	2014-10-24 15:47:43.490459
1303	35	Entrevistas	http://bit.ly/1zRsZXN	1301	t	t	2014-10-24 15:47:43.490789
1304	35	Letras de músicas em inglês	\N	1301	t	t	2014-10-24 15:47:43.491165
1305	35	Estrangeirismos	http://bit.ly/1fL53J8	1304	t	t	2014-10-24 15:47:43.491499
1306	35	Falsos cognatos	http://bit.ly/1hrzFk2	1304	t	t	2014-10-24 15:47:43.491836
1307	35	Falsos cognatos 2	http://escoladigital.org.br/falsos-cognatos/	1304	t	t	2014-10-24 15:47:43.492218
1308	35	Tipologia textual:	\N	1301	t	t	2014-10-24 15:47:43.492555
1309	35	Texto publicitário	\N	1308	t	t	2014-10-24 15:47:43.492888
1310	35	Letras de música	\N	1308	t	t	2014-10-24 15:47:43.493377
1311	35	Revisão das estruturas gramaticais:	\N	1301	t	t	2014-10-24 15:47:43.493709
1312	35	Presente simples	\N	1311	t	t	2014-10-24 15:47:43.494077
1313	35	Advérbios de freqüência	\N	1311	t	t	2014-10-24 15:47:43.494419
1314	35	Passado simples	\N	1311	t	t	2014-10-24 15:47:43.494751
1315	35	Verbos regulares e irregulares	\N	1311	t	t	2014-10-24 15:47:43.49516
1316	35	Estruturas gramaticais:	\N	1294	t	t	2014-10-24 15:47:43.495493
1317	35	Verbos modais como “may, might, must, should”	\N	1316	t	t	2014-10-24 15:47:43.495826
1318	35	Futuro simples com “will”	\N	1316	t	t	2014-10-24 15:47:43.496195
1319	35	Pronomes indefinidos: some,any	\N	1316	t	t	2014-10-24 15:47:43.496533
1320	35	Grau adjetivos	\N	1316	t	t	2014-10-24 15:47:43.496863
1321	35	Vocabulário:	\N	1301	t	t	2014-10-24 15:47:43.497341
1322	35	Clima	\N	1321	t	t	2014-10-24 15:47:43.497675
1323	35	Estações do ano	\N	1321	t	t	2014-10-24 15:47:43.498135
1324	35	Fenômenos climáticos	\N	1321	t	t	2014-10-24 15:47:43.498476
1325	35	Atividades vocábulos relacionados ao dia do ambiente	\N	1321	t	t	2014-10-24 15:47:43.498807
1326	35	Lugares da região: como rios, montanhas, praia, fenômenos da natureza) Doenças	\N	1321	t	t	2014-10-24 15:47:43.49923
1327	35	Celebrations:	\N	1301	t	t	2014-10-24 15:47:43.499564
1328	35	Environment Day	\N	1327	t	t	2014-10-24 15:47:43.499899
1329	35	Valentines Day	\N	1327	t	t	2014-10-24 15:47:43.500276
1330	35	Saint John Festival	\N	1327	t	t	2014-10-24 15:47:43.500615
1331	35	Christmas Day	\N	1327	t	t	2014-10-24 15:47:43.500948
1332	35	ENSINO MÉDIO – 3ª série	\N	\N	t	t	2014-10-24 15:47:43.501354
1333	35	Revisão de verbos modais (have to/ don´t have to, should /shouldn´t/ must)	\N	1332	t	t	2014-10-24 15:47:43.501675
1334	35	Substantivos compostos	\N	1332	t	t	2014-10-24 15:47:43.502123
1335	35	Graus do adjetivo (comparativo e superlativo)	\N	1332	t	t	2014-10-24 15:47:43.502462
1336	35	If clauses (zero and first conditional)	\N	1332	t	t	2014-10-24 15:47:43.502799
1337	35	Vocabulário:	\N	1332	t	t	2014-10-24 15:47:43.503163
1338	35	Preços	\N	1337	t	t	2014-10-24 15:47:43.503498
1339	35	Comida e bebida	\N	1337	t	t	2014-10-24 15:47:43.503828
1340	35	Refeições	\N	1337	t	t	2014-10-24 15:47:43.50424
1341	35	Doenças	\N	1337	t	t	2014-10-24 15:47:43.504579
1342	35	Expressões relacionadas a aconselhamento	\N	1337	t	t	2014-10-24 15:47:43.504916
1346	35	Presente perfeito contínuo	\N	1345	t	t	2014-10-24 15:47:43.506444
1347	35	Passado perfeito	\N	1345	t	t	2014-10-24 15:47:43.506781
1348	35	Gerúndio e Infinitivo (ing)	\N	1345	t	t	2014-10-24 15:47:43.507163
1349	35	If- clauses (second and third conditionals)	\N	1345	t	t	2014-10-24 15:47:43.5075
1350	35	Passive Voice	\N	1345	t	t	2014-10-24 15:47:43.50783
1351	35	Estruturas gramaticais:	\N	1259	t	t	2014-10-24 15:47:43.508242
1352	35	Revisão do emprego de pronomes (sujeito, objeto, reflexivo, possessivos)	\N	1351	t	t	2014-10-24 15:47:43.508577
1353	35	Outras estruturas gramaticais	\N	1351	t	t	2014-10-24 15:47:43.508913
1354	35	Caso genitivo	\N	1351	t	t	2014-10-24 15:47:43.509365
1355	35	Pronomes relativos	\N	1351	t	t	2014-10-24 15:47:43.509715
1356	35	Reported Speech	\N	1351	t	t	2014-10-24 15:47:43.510138
671	36	ENSINO MÉDIO – 1.ª Série	\N	\N	t	t	2014-10-24 15:47:43.510474
672	36	ASSUNTOS BÁSICOS	\N	671	t	t	2014-10-24 15:47:43.510794
673	36	POTENCIAÇÃO E RADICIAÇÃO	http://bit.ly/YTcTA0	672	t	t	2014-10-24 15:47:43.511162
674	36	FATORAÇÃO	http://bit.ly/1uqx9Tr	672	t	t	2014-10-24 15:47:43.511499
675	36	MMC E MDC	http://bit.ly/Xj1W95	672	t	t	2014-10-24 15:47:43.511832
676	36	GRANDEZAS PROPORCIONAIS	http://bit.ly/Xj2mwk	672	t	t	2014-10-24 15:47:43.51221
677	36	PORCENTAGEM	http://bit.ly/WO7KYh	672	t	t	2014-10-24 15:47:43.512623
678	36	MATEMÁTICA FINANCEIRA	http://bit.ly/1pjP9Ky	672	t	t	2014-10-24 15:47:43.512956
679	36	ARITMÉTICA BÁSICA	http://bit.ly/1pjPsVJ	672	t	t	2014-10-24 15:47:43.513313
680	36	EQUAÇÕES	http://bit.ly/1pjPdd7	672	t	t	2014-10-24 15:47:43.513663
681	36	INEQUAÇÕES	http://bit.ly/YTeZQy	672	t	t	2014-10-24 15:47:43.514097
682	36	CONJUNTOS E NÚMEROS	\N	671	t	t	2014-10-24 15:47:43.514448
683	36	Teoria dos Conjuntos	http://bit.ly/1pjPsVJ	682	t	t	2014-10-24 15:47:43.514784
684	36	Conjuntos numéricos	http://bit.ly/1uSD4no	682	t	t	2014-10-24 15:47:43.515161
685	36	Conjunto dos números Naturais	http://bit.ly/Xj5Cb4	684	t	t	2014-10-24 15:47:43.5155
686	36	Conjunto dos números Inteiros	http://bit.ly/Xj5Sqy	684	t	t	2014-10-24 15:47:43.515831
687	36	Conjunto dos números Racionais	http://bit.ly/1qZbZfL	684	t	t	2014-10-24 15:47:43.516242
688	36	Conjunto dos números Irracionais	http://bit.ly/YTfQk0	684	t	t	2014-10-24 15:47:43.516577
689	36	Conjunto dos números Reais (Intervalos numéricos)	http://bit.ly/YTg0b5	684	t	t	2014-10-24 15:47:43.516924
690	36	Múltiplos	http://bit.ly/YThAtC	682	t	t	2014-10-24 15:47:43.517309
691	36	Divisores	http://bit.ly/XjcepJ	682	t	t	2014-10-24 15:47:43.517644
692	36	Lógica	http://bit.ly/YThLoT	682	t	t	2014-10-24 15:47:43.518028
693	36	FUNÇÕES E RELAÇÕES	\N	671	t	t	2014-10-24 15:47:43.518376
694	36	RELAÇÕES	\N	693	t	t	2014-10-24 15:47:43.518706
695	36	Par ordenado	http://bit.ly/1qZfRxc	694	t	t	2014-10-24 15:47:43.519126
696	36	Produto Cartesiano	http://bit.ly/YTjtqa	694	t	t	2014-10-24 15:47:43.519477
697	36	Relações	\N	694	t	t	2014-10-24 15:47:43.519812
698	36	Relações inversas	\N	694	t	t	2014-10-24 15:47:43.520248
699	36	FUNÇÕES	\N	693	t	t	2014-10-24 15:47:43.520591
700	36	Funções: Conceitos gerais	http://bit.ly/1qvfXZ5	699	t	t	2014-10-24 15:47:43.520949
701	36	Função Polinomial do 1ª grau (ou Função Afim)	http://bit.ly/1mbhL9x	699	t	t	2014-10-24 15:47:43.521374
702	36	Função Polinomial do 2º grau (ou Função Quadrática)	http://bit.ly/Qo5HHU	699	t	t	2014-10-24 15:47:43.521712
703	36	Função Modular	\N	699	t	t	2014-10-24 15:47:43.522137
704	36	Função Composta	\N	699	t	t	2014-10-24 15:47:43.522476
705	36	Funções Inversas	http://bit.ly/YTmx5z	699	t	t	2014-10-24 15:47:43.522807
706	36	EXPONENCIAIS E LOGARÍTMOS	\N	671	t	t	2014-10-24 15:47:43.523154
707	36	Função Exponencial	http://bit.ly/1mbh53N	706	t	t	2014-10-24 15:47:43.523491
708	36	Exponenciais e Função exponencial	http://bit.ly/1mbh53N	707	t	t	2014-10-24 15:47:43.523832
709	36	Equações exponenciais	http://bit.ly/1qZo18L	707	t	t	2014-10-24 15:47:43.524205
710	36	Inequações exponenciais	http://bit.ly/1p61ibx	707	t	t	2014-10-24 15:47:43.524536
711	36	Logarítmos	http://bit.ly/1r1HMv2	706	t	t	2014-10-24 15:47:43.524873
712	36	Função Logarítmica	http://bit.ly/Qo6rg2	711	t	t	2014-10-24 15:47:43.525269
713	36	Equações logarítmicas	http://bit.ly/1qZoPdI	711	t	t	2014-10-24 15:47:43.525606
714	36	Inequações logarítmicas	http://bit.ly/Xjtmvu	711	t	t	2014-10-24 15:47:43.525941
715	36	SEQUÊNCIAS E SÉRIES	\N	671	t	t	2014-10-24 15:47:43.526312
716	36	Sequências	http://bit.ly/1lrDZa2	715	t	t	2014-10-24 15:47:43.526645
717	36	Progressão Aritmética (P.A)	http://bit.ly/1piKDyc	715	t	t	2014-10-24 15:47:43.527099
718	36	Progressão Geométrica (P.G)	http://bit.ly/1piX7Gf	715	t	t	2014-10-24 15:47:43.527454
719	36	Séries Infinitas	\N	715	t	t	2014-10-24 15:47:43.52779
720	36	MATEMÁTICA FINANCEIRA	\N	671	t	t	2014-10-24 15:47:43.528163
721	36	Razão	http://bit.ly/1lrS0Ez	720	t	t	2014-10-24 15:47:43.528495
722	36	Proporção	http://bit.ly/Qo1rrW	720	t	t	2014-10-24 15:47:43.528833
723	36	Regra de três simples	http://bit.ly/1r1IJ6S	722	t	t	2014-10-24 15:47:43.529273
724	36	Regra de três composta	\N	722	t	t	2014-10-24 15:47:43.529609
725	36	Regra de sociedade	\N	722	t	t	2014-10-24 15:47:43.529946
726	36	Porcentagem	http://bit.ly/WO7KYh	720	t	t	2014-10-24 15:47:43.530303
727	36	Juros	http://bit.ly/1tmYVS4	720	t	t	2014-10-24 15:47:43.530636
728	36	Juros Simples	http://bit.ly/1qhjXfc	727	t	t	2014-10-24 15:47:43.531084
729	36	Juros Compostos	http://bit.ly/1qhjYzw	727	t	t	2014-10-24 15:47:43.531442
730	36	Descontos	\N	720	t	t	2014-10-24 15:47:43.531777
731	36	Desconto Simples	\N	730	t	t	2014-10-24 15:47:43.532163
732	36	Desconto Composto	\N	730	t	t	2014-10-24 15:47:43.532505
733	36	Planos de pagamento	\N	720	t	t	2014-10-24 15:47:43.532839
734	36	ENSINO MÉDIO – 2.ª Série	\N	\N	t	t	2014-10-24 15:47:43.533201
735	36	TRIÂNGULO RETÂNGULO	\N	734	t	t	2014-10-24 15:47:43.533561
736	36	TRIGONOMETRIA	http://bit.ly/YqH9ll	735	t	t	2014-10-24 15:47:43.533898
737	36	TRIGONOMETRIA NO TRIÂNGULO RETÂNGULO	http://bit.ly/XKspNA	736	t	t	2014-10-24 15:47:43.534342
738	36	Funções Trigonométricas circulares	http://bit.ly/XKsOzn	736	t	t	2014-10-24 15:47:43.534773
739	36	Círculo trigonométrico	http://bit.ly/1qhkiOX	738	t	t	2014-10-24 15:47:43.535153
740	36	FUNÇÕES CIRCULARES:	http://bit.ly/1qhkjCl	738	t	t	2014-10-24 15:47:43.535487
741	36	Seno	http://bit.ly/ZrqheA	740	t	t	2014-10-24 15:47:43.535831
742	36	cosseno	http://bit.ly/Xjkiqz	740	t	t	2014-10-24 15:47:43.536193
743	36	Tangente	http://bit.ly/1tn7ix5	740	t	t	2014-10-24 15:47:43.536529
744	36	cotangente	\N	740	t	t	2014-10-24 15:47:43.536863
745	36	Secante	\N	740	t	t	2014-10-24 15:47:43.537227
746	36	cossecante	\N	740	t	t	2014-10-24 15:47:43.537556
747	36	RELAÇÕES E IDENTIDADES	http://bit.ly/1tn8ZKU	738	t	t	2014-10-24 15:47:43.53789
748	36	Identidades	http://bit.ly/1tn8ZKU	747	t	t	2014-10-24 15:47:43.538316
749	36	Soma e subtração de arcos	\N	747	t	t	2014-10-24 15:47:43.538657
750	36	Arco duplo	http://bit.ly/XKDjml	747	t	t	2014-10-24 15:47:43.53913
751	36	arco metade	\N	747	t	t	2014-10-24 15:47:43.539467
752	36	Transformação em produto	\N	747	t	t	2014-10-24 15:47:43.539829
753	36	Equações trigonométricas	http://bit.ly/XKEIt1	736	t	t	2014-10-24 15:47:43.540197
754	36	Inequações trigonométricas	http://bit.ly/1tnhllH	736	t	t	2014-10-24 15:47:43.54053
755	36	Funções trigonométricas inversas	\N	736	t	t	2014-10-24 15:47:43.540866
756	36	Funções trigonométrica em triângulos quaisquer: Lei do seno, Lei dos cossenos, Teorema da área	http://bit.ly/1tnj4rj	736	t	t	2014-10-24 15:47:43.541235
757	36	MATRIZES E DETERMINANTES	\N	734	t	t	2014-10-24 15:47:43.541571
758	36	Matrizes	http://bit.ly/1tnkLVI	757	t	t	2014-10-24 15:47:43.541905
759	36	Operações com Matrizes	http://bit.ly/XKJs1X	758	t	t	2014-10-24 15:47:43.542278
760	36	Matriz inversa	http://bit.ly/1tnm9aU	758	t	t	2014-10-24 15:47:43.542612
761	36	Determinantes	http://bit.ly/XKLG1i	757	t	t	2014-10-24 15:47:43.543003
762	36	SISTEMAS	http://bit.ly/XKM8fY	757	t	t	2014-10-24 15:47:43.543392
763	36	Sistemas Lineares	http://bit.ly/Zrvkfe	762	t	t	2014-10-24 15:47:43.543814
764	36	Sistemas Não Lineares	\N	762	t	t	2014-10-24 15:47:43.544168
765	36	BINÔMIO DE NEWTON	\N	734	t	t	2014-10-24 15:47:43.544501
766	36	Fatorial	http://bit.ly/ZrvqmR	765	t	t	2014-10-24 15:47:43.544836
767	36	Triângulo de Pascal e suas propriedades	http://bit.ly/XKUtjQ	765	t	t	2014-10-24 15:47:43.545201
768	36	ESTATÍSTICA	\N	734	t	t	2014-10-24 15:47:43.545535
769	36	Análise Combinatória (ou contagem):	http://bit.ly/ZrvByx	768	t	t	2014-10-24 15:47:43.545865
770	36	Fatorial	http://bit.ly/ZrvqmR	769	t	t	2014-10-24 15:47:43.546221
771	36	Princípio Fundamental da Contagem	http://bit.ly/XKVEjj	769	t	t	2014-10-24 15:47:43.546554
772	36	Permutação	http://bit.ly/1tnyA6r	769	t	t	2014-10-24 15:47:43.546906
773	36	Arranjos	http://bit.ly/ZrvPFY	769	t	t	2014-10-24 15:47:43.547274
774	36	Combinação	http://bit.ly/ZrvTFQ	769	t	t	2014-10-24 15:47:43.547611
775	36	Probabilidade	http://bit.ly/XGp56s	768	t	t	2014-10-24 15:47:43.547945
776	36	Estatística	http://bit.ly/1tnx0BF	768	t	t	2014-10-24 15:47:43.548304
777	36	ENSINO MÉDIO – 3.ª Série	\N	\N	t	t	2014-10-24 15:47:43.548637
778	36	GEOMETRIA:	\N	777	t	t	2014-10-24 15:47:43.549022
779	36	GEOMETRIA PLANA	http://bit.ly/XKXuAz	778	t	t	2014-10-24 15:47:43.54939
780	36	NOÇÕES PRIMITIVAS	\N	779	t	t	2014-10-24 15:47:43.549721
781	36	ÂNGULO	http://bit.ly/XKYlkE	779	t	t	2014-10-24 15:47:43.550138
782	36	PARALELISMO	http://bit.ly/1tnBQ1N	779	t	t	2014-10-24 15:47:43.55048
783	36	PERPENDICULARISMO	\N	779	t	t	2014-10-24 15:47:43.550811
784	36	TEOREMA DE TALES	http://bit.ly/ZrVZsm	779	t	t	2014-10-24 15:47:43.551245
785	36	TRIÂNGULOS	http://bit.ly/XMN8Aj	779	t	t	2014-10-24 15:47:43.551587
786	36	QUADRILÁTEROS	http://bit.ly/XMNwia	779	t	t	2014-10-24 15:47:43.551934
787	36	POLÍGONOS	http://bit.ly/ZrWO4l	779	t	t	2014-10-24 15:47:43.552351
788	36	CIRCUNFERÊNCIA	\N	779	t	t	2014-10-24 15:47:43.552839
789	36	CÍRCULO	http://bit.ly/XMPxem	779	t	t	2014-10-24 15:47:43.553201
790	36	ÁREA DE FIGURAS PLANAS	http://bit.ly/1qhDlIO	779	t	t	2014-10-24 15:47:43.553538
791	36	GEOMETRIA ESPACIAL	\N	778	t	t	2014-10-24 15:47:43.553884
792	36	Geometria de posição	\N	791	t	t	2014-10-24 15:47:43.554246
793	36	Sólidos geométricos (Propriedades, área e volume, Princípio de Cavalieri)	http://bit.ly/1qhDvjz	791	t	t	2014-10-24 15:47:43.554588
794	36	Poliedros	http://bit.ly/1qhDxI0	793	t	t	2014-10-24 15:47:43.554921
795	36	Prismas	http://bit.ly/XMTe3G	793	t	t	2014-10-24 15:47:43.555283
796	36	Corpos redondos	http://bit.ly/ZrYmLF	793	t	t	2014-10-24 15:47:43.555648
797	36	 Cilindro	http://bit.ly/ZrYs65	796	t	t	2014-10-24 15:47:43.556076
798	36	 Cone	http://bit.ly/1tpAIKY	796	t	t	2014-10-24 15:47:43.556433
799	36	 Esfera	http://bit.ly/1qhDQ5P	796	t	t	2014-10-24 15:47:43.556766
800	36	Tronco de cone	http://bit.ly/ZrZCOJ	791	t	t	2014-10-24 15:47:43.557202
801	36	Tronco de pirâmide	http://bit.ly/XMXEHK	791	t	t	2014-10-24 15:47:43.557563
802	36	Geometria métrica espacial (Relações métricas nos sólidos geométricos)	\N	791	t	t	2014-10-24 15:47:43.557899
803	36	GEOMETRIA ANALÍTICA	\N	778	t	t	2014-10-24 15:47:43.558321
804	36	O ponto	\N	803	t	t	2014-10-24 15:47:43.558667
805	36	Coordenadas cartesianas	http://bit.ly/XNihn2	804	t	t	2014-10-24 15:47:43.559061
806	36	Ponto médio	http://bit.ly/1qhGyIz	804	t	t	2014-10-24 15:47:43.559405
807	36	Coordenadas do Baricentro	http://bit.ly/Zsc5SL	804	t	t	2014-10-24 15:47:43.559793
808	36	Distância entre dois pontos	\N	804	t	t	2014-10-24 15:47:43.56019
809	36	Condição de alinhamento de três pontos)	\N	804	t	t	2014-10-24 15:47:43.560526
810	36	A reta	\N	803	t	t	2014-10-24 15:47:43.56086
811	36	Equações da reta	http://bit.ly/1tq2yXw	810	t	t	2014-10-24 15:47:43.561219
812	36	Coordenadas dos pontos de intersecção de retas	\N	810	t	t	2014-10-24 15:47:43.561555
813	36	Posições relativas entre retas	\N	810	t	t	2014-10-24 15:47:43.561915
814	36	Ângulo entre duas retas	http://bit.ly/1qhHkoP	810	t	t	2014-10-24 15:47:43.562337
815	36	Distância entre ponto e reta	http://bit.ly/1qhHmgq	810	t	t	2014-10-24 15:47:43.5627
816	36	O plano	\N	804	t	t	2014-10-24 15:47:43.563086
817	36	área de polígonos no plano	http://bit.ly/ZseI78	816	t	t	2014-10-24 15:47:43.563427
818	36	Inequações do 1º grau	http://bit.ly/XNtrby	816	t	t	2014-10-24 15:47:43.563766
819	36	regiões planas	\N	816	t	t	2014-10-24 15:47:43.564119
820	36	Circunferência	\N	803	t	t	2014-10-24 15:47:43.564457
821	36	Equações da circunferência	\N	820	t	t	2014-10-24 15:47:43.564788
822	36	Posição de um ponto em relação a uma circunferência	\N	820	t	t	2014-10-24 15:47:43.565197
823	36	Posição de uma reta em relação a uma circunferência	\N	820	t	t	2014-10-24 15:47:43.565534
824	36	Condição de tangência entre reta e circunferência	\N	820	t	t	2014-10-24 15:47:43.578238
825	36	Posições relativas de duas circunferências	\N	820	t	t	2014-10-24 15:47:43.578701
826	36	Cônicas	\N	803	t	t	2014-10-24 15:47:43.579127
827	36	Elipse	http://bit.ly/ZsefSe	826	t	t	2014-10-24 15:47:43.5796
828	36	hipérbole	http://bit.ly/1tq6VC0	826	t	t	2014-10-24 15:47:43.580063
829	36	Parábola	http://bit.ly/1qhHIDE	826	t	t	2014-10-24 15:47:43.58051
830	36	NÚMEROS COMPLEXOS	\N	777	t	t	2014-10-24 15:47:43.581036
831	36	A unidade imaginária	http://bit.ly/1qhIaC2	830	t	t	2014-10-24 15:47:43.58147
832	36	Conjunto dos números complexos	http://bit.ly/ZsfmS7	830	t	t	2014-10-24 15:47:43.581886
833	36	O número complexo e suas partes	http://bit.ly/1qhEO1U	830	t	t	2014-10-24 15:47:43.582353
834	36	Classificação de números complexos	\N	830	t	t	2014-10-24 15:47:43.582764
835	36	Igualdade de números complexos	\N	830	t	t	2014-10-24 15:47:43.583288
836	36	Operações com números complexos na forma algébrica	\N	830	t	t	2014-10-24 15:47:43.583745
837	36	Plano de Argand – Gauss	\N	830	t	t	2014-10-24 15:47:43.584259
838	36	Módulo e argumento	\N	830	t	t	2014-10-24 15:47:43.584675
839	36	Forma trigonométrica de um número complexo	http://bit.ly/ZsgsNH	830	t	t	2014-10-24 15:47:43.585108
840	36	Operações na forma trigonométrica	\N	830	t	t	2014-10-24 15:47:43.585518
841	36	Forma Algébrica e operações	\N	830	t	t	2014-10-24 15:47:43.585942
842	36	Forma trigonométrica e operações	\N	830	t	t	2014-10-24 15:47:43.586431
843	36	POLINÔMIOS	\N	777	t	t	2014-10-24 15:47:43.586846
844	36	Polinômios	http://bit.ly/XNz6y8	843	t	t	2014-10-24 15:47:43.587266
845	36	Equações polinomiais (OU ALGÉBRICAS)	http://bit.ly/1qiRpSr	843	t	t	2014-10-24 15:47:43.5877
846	36	DESENHO GEOMÉTRICO	http://bit.ly/1wO5Nuz	777	t	t	2014-10-24 15:47:43.588167
847	36	LIMITE E DERIVADA	\N	777	t	t	2014-10-24 15:47:43.588633
848	36	LIMITE DE UMA FUNÇÃO	http://bit.ly/1BUwHRi	847	t	t	2014-10-24 15:47:43.589182
849	36	DERIVADA	\N	777	t	t	2014-10-24 15:47:43.589707
850	36	DERIVADA DE UMA FUNÇÃO	http://bit.ly/1BUxxxs	849	t	t	2014-10-24 15:47:43.590304
851	36	APLICAÇÃO DA DERIVADA	http://bit.ly/1BUxxxs	849	t	t	2014-10-24 15:47:43.590882
852	36	DERIVADA IMPLÍCITA	http://bit.ly/1BUxxxs	849	t	t	2014-10-24 15:47:43.591526
853	36	INTEGRAIS	\N	777	t	t	2014-10-24 15:47:43.592114
854	36	INTEGRAIS DEFINIDAS	http://bit.ly/1tyPlf7	853	t	t	2014-10-24 15:47:43.59264
855	36	INTEGRAIS INDEFINIDAS	http://bit.ly/1tyPlf7	853	t	t	2014-10-24 15:47:43.593183
856	36	INTEGRAIS IMPRÓPRIAS	http://bit.ly/1tyPlf7	853	t	t	2014-10-24 15:47:43.593715
857	36	INTEGRAIS DUPLAS	\N	853	t	t	2014-10-24 15:47:43.594312
151	37	ENSINO MÉDIO – 1.ª Série	\N	\N	t	t	2014-10-24 15:47:43.594822
152	37	INTRODUÇAO À SOCIOLOGIA	\N	151	t	t	2014-10-24 15:47:43.595323
153	37	A sociedade humana: por que somos seres sociais?	http://bit.ly/1uiytuA	152	t	t	2014-10-24 15:47:43.595828
154	37	O estudo da Sociologia – a produção social do conhecimento. (O surgimento da Sociologia: contexto histórico/Feudalismo/ 1ª Rev. Industrial/ Rev. Francesa/Iluminismo)	http://bit.ly/1mswYFZ	152	t	t	2014-10-24 15:47:43.596346
155	37	A sociologia: conceito e objeto de estudo	http://bit.ly/1mswYFZ	152	t	t	2014-10-24 15:47:43.596866
156	37	Divisão das Ciências Sociais	http://bit.ly/1mswYFZ	152	t	t	2014-10-24 15:47:43.597388
157	37	A Sociologia no Ensino Médio: objetivo e importância	http://bit.ly/1mswYFZ	152	t	t	2014-10-24 15:47:43.597912
158	37	O indivíduo, sua história e a sociedade	http://bit.ly/1dmIujj	152	t	t	2014-10-24 15:47:43.59845
159	37	O homem, um ser social	http://bit.ly/1uiytuA	152	t	t	2014-10-24 15:47:43.599057
160	37	O estudo da Sociologia: conceito, objeto de estudo, origem histórica	http://bit.ly/1mswYFZ	152	t	t	2014-10-24 15:47:43.599571
161	37	A Sociologia no Ensino Médio: objetivo e importância	http://bit.ly/1mswYFZ	152	t	t	2014-10-24 15:47:43.600187
162	37	A SOCIEDADE DOS INDIVÍDUOS	\N	151	t	t	2014-10-24 15:47:43.600712
163	37	O processo de socialização (Instituições, Grupos Sociais)	http://bit.ly/1dmIujj	162	t	t	2014-10-24 15:47:43.601227
164	37	As relações entre indivíduo e sociedade através das contribuições dos teóricos clássicos – Émile Durkheim, Karl Marx e Max Weber – uma breve introdução.	http://bit.ly/1dmIujj	162	t	t	2014-10-24 15:47:43.601736
165	37	O LUGAR DOS GRUPOS HUMANOS NA SOCIEDADE.	\N	151	t	t	2014-10-24 15:47:43.6023
166	37	O processo de socialização e agrupamentos sociais:	http://bit.ly/1dmIujj	165	t	t	2014-10-24 15:47:43.602819
167	37	Instituições Sociais	\N	166	t	t	2014-10-24 15:47:43.6035
168	37	Grupos Sociais	\N	166	t	t	2014-10-24 15:47:43.604155
169	37	Agregados Sociais	\N	166	t	t	2014-10-24 15:47:43.604681
170	37	As relações entre Indivíduo, grupo e sociedade através das contribuições dos teóricos clássicos:	http://bit.ly/1dmIujj	165	t	t	2014-10-24 15:47:43.605235
171	37	Karl Marx	\N	170	t	t	2014-10-24 15:47:43.605744
172	37	Émile Durkheim	\N	170	t	t	2014-10-24 15:47:43.606283
173	37	Max Weber .	\N	170	t	t	2014-10-24 15:47:43.60679
174	37	CULTURA E IDEOLOGIA.	\N	151	t	t	2014-10-24 15:47:43.607303
175	37	Conceitos e definições. (Definição de Cultura e Ideologia)	http://bit.ly/1jC2Qn2	174	t	t	2014-10-24 15:47:43.60781
176	37	Cultura segundo a Antropologia	http://bit.ly/1dmQqko	174	t	t	2014-10-24 15:47:43.608331
177	37	Etnocentrismo, contracultura	http://bit.ly/1msEPmZ	174	t	t	2014-10-24 15:47:43.608839
178	37	Cultura e ideologia.	http://bit.ly/1jC2Qn2	174	t	t	2014-10-24 15:47:43.609372
179	37	Cultura popular e cultura erudita.	http://bit.ly/1jC2Qn2	174	t	t	2014-10-24 15:47:43.609947
180	37	Cultura de massa e indústria cultural no Brasil.	http://bit.ly/1msGnxm	174	t	t	2014-10-24 15:47:43.610495
181	37	Diversidade cultural.	http://bit.ly/1jC7zVY	174	t	t	2014-10-24 15:47:43.611068
182	37	FORMAÇÃO DO POVO BRASILEIRO – AS TRÊS RAÇAS. (FORMAÇÃO DA IDENTIDADE NACIONAL	\N	151	t	t	2014-10-24 15:47:43.611648
183	37	Raça/Etnia/Povo. (Conceitos de Raça, Etnia e Povo)	http://bit.ly/1dmRtAR	182	t	t	2014-10-24 15:47:43.61216
184	37	Conceitos e significados	http://bit.ly/1dmRtAR	182	t	t	2014-10-24 15:47:43.61267
185	37	Preconceito e discriminação racial	http://bit.ly/1dnaYte	182	t	t	2014-10-24 15:47:43.613231
186	37	O mito da democracia racial no Brasil.http://bit.ly/1gVHrSl	http://bit.ly/1dmRtAR	182	t	t	2014-10-24 15:47:43.61374
187	37	A formação das identidades individuais e coletivas.	http://bit.ly/1gVHrSl	182	t	t	2014-10-24 15:47:43.614297
188	37	As diversas identidades brasileiras na contemporaneidade.	http://bit.ly/1gVHrSl	182	t	t	2014-10-24 15:47:43.614799
189	37	As identidades locais e a globalização.	http://bit.ly/1gVHrSl	182	t	t	2014-10-24 15:47:43.61532
191	37	ENSINO MÉDIO – 2.ª Série	\N	\N	t	t	2014-10-24 15:47:43.615826
192	37	PODER, POLÍTICA E ESTADO	\N	191	t	t	2014-10-24 15:47:43.616321
193	37	O Estado Moderno.	http://bit.ly/1gVWMSP	192	t	t	2014-10-24 15:47:43.616835
194	37	O poder e o Estado.	http://bit.ly/PUgBoz	192	t	t	2014-10-24 15:47:43.617353
195	37	Poder, política e Estado no Brasil	http://bit.ly/PUgBoz	192	t	t	2014-10-24 15:47:43.617861
196	37	A democracia no Brasil	http://bit.ly/1dndC2a	192	t	t	2014-10-24 15:47:43.618388
197	37	PODER, POLÍTICA, ESTADO E CIDADANIA	\N	191	t	t	2014-10-24 15:47:43.618894
198	37	O surgimento da Sociologia no contexto da Revolução Industrial e da Revolução Francesa.	http://bit.ly/1i07jik	197	t	t	2014-10-24 15:47:43.619472
199	37	Surgimento da classe social (o proletariado)	http://bit.ly/1jBYgVU	198	t	t	2014-10-24 15:47:43.619987
200	37	O povo durante a Revolução Francesa.	http://bit.ly/1hVC72x	198	t	t	2014-10-24 15:47:43.620502
201	37	A constituição do Estado Moderno: do ideal de liberdade para o Princípio da dignidade da pessoa Humana.	http://bit.ly/1gVWMSP	197	t	t	2014-10-24 15:47:43.621119
202	37	Os elementos constitutivos do Estado Moderno (povo, território, soberania)	http://bit.ly/1gVWMSP	197	t	t	2014-10-24 15:47:43.621627
203	37	O normal e o patológico nas instituições (Émile Durkheim)	\N	197	t	t	2014-10-24 15:47:43.622181
204	37	A ESTRUTURA SOCIAL E AS DESIGUALDADES.	\N	191	t	t	2014-10-24 15:47:43.622692
205	37	Estrutura e estratificação social	http://bit.ly/1rxCwRz	204	t	t	2014-10-24 15:47:43.623294
206	37	A sociedade capitalista e as classes sociais	http://bit.ly/1jBYgVU	204	t	t	2014-10-24 15:47:43.623806
207	37	As classes Sociais no Brasil	http://bit.ly/1olCI6i	204	t	t	2014-10-24 15:47:43.624368
208	37	Pensamento Sociológico Brasileiro (Gilberto Freyre, Darcy Ribeiro, Sérgio Buarque de Holanda, Otavio Ianni, Florestan Fernandes,Fernando Henrique Cardoso, Roberto da Mata, Caio Prado Junior, Celso Furtado, etc.)	http://bit.ly/1olCI6i	204	t	t	2014-10-24 15:47:43.624875
209	37	As desigualdades sociais no Brasil	http://bit.ly/1dneOCB	204	t	t	2014-10-24 15:47:43.625427
210	37	A ESTRUTURA SOCIAL E CLASSES SOCIAIS.	\N	191	t	t	2014-10-24 15:47:43.62593
211	37	Estrutura e estratificação social.	http://bit.ly/1hZehTz	210	t	t	2014-10-24 15:47:43.626457
212	37	A sociedade capitalista e as classes sociais	http://bit.ly/1jBYgVU	210	t	t	2014-10-24 15:47:43.627013
213	37	Desigualdades Sociais (pobreza e exclusão social)	http://bit.ly/1dneOCB	210	t	t	2014-10-24 15:47:43.627544
214	37	As classes sociais no Brasil Império e República	http://bit.ly/1olCI6i	210	t	t	2014-10-24 15:47:43.62817
215	37	Conflitos Sociais	http://bit.ly/1jDIPfM	210	t	t	2014-10-24 15:47:43.628678
216	37	DIREITOS, CIDADANIA E MOVIMENTOS SOCIAIS.	\N	191	t	t	2014-10-24 15:47:43.629282
217	37	Direitos e cidadania	http://bit.ly/1dnaYte	216	t	t	2014-10-24 15:47:43.629788
218	37	Os movimentos sociais	http://bit.ly/1mt2X8W	216	t	t	2014-10-24 15:47:43.630341
219	37	Direitos e cidadania no Brasil	http://bit.ly/1dnaYte	216	t	t	2014-10-24 15:47:43.630848
220	37	Os movimentos sociais no Brasil	http://bit.ly/1dnaYte	216	t	t	2014-10-24 15:47:43.631365
221	37	MUDANÇAS SOCIAIS E MOVIMENTOS SOCIAIS.	\N	191	t	t	2014-10-24 15:47:43.631872
222	37	Movimentos sociais	http://bit.ly/1mt2X8W	221	t	t	2014-10-24 15:47:43.63239
223	37	Origem dos movimentos sociais	http://bit.ly/1mt2X8W	221	t	t	2014-10-24 15:47:43.632899
224	37	Os movimentos sociais na cidade	http://bit.ly/1mt2X8W	221	t	t	2014-10-24 15:47:43.633456
225	37	Os movimentos sociais no campo	http://bit.ly/1mt2X8W	221	t	t	2014-10-24 15:47:43.634015
226	37	Mudanças espaciais, políticas e sociais	\N	221	t	t	2014-10-24 15:47:43.634564
227	37	Globalização e direitos humanos e sociais (Declaração Universal dos Direitos Humanos).	http://bit.ly/1dnaYte	221	t	t	2014-10-24 15:47:43.635153
228	37	Democracia, consciência política e a participação política	http://bit.ly/1dndC2a	221	t	t	2014-10-24 15:47:43.635663
229	37	A SOCIEDADE DA INFORMAÇÃO	\N	191	t	t	2014-10-24 15:47:43.636214
230	37	O direito à informação e o conhecimento	http://bit.ly/1hZXO2y	229	t	t	2014-10-24 15:47:43.636723
231	37	Os meios de comunicação e as novas tecnologias	http://bit.ly/1mFEgpT	229	t	t	2014-10-24 15:47:43.637317
232	37	A democratização das mídias e o saber local e global	http://bit.ly/1mFE36k	229	t	t	2014-10-24 15:47:43.637823
233	37	As tecnologias da Comunicação e Informação (o espaço virtual e o espaço real)	http://bit.ly/1hZXO2y	229	t	t	2014-10-24 15:47:43.638363
234	37	A democratização das mídias e o saber local e global	http://bit.ly/1hZXO2y	229	t	t	2014-10-24 15:47:43.638871
235	37	As redes sociais, comportamentos sociais e virtuais na atualidade.	http://bit.ly/1mFEgpT	229	t	t	2014-10-24 15:47:43.63941
236	37	As relações sociais e a nanotecnologia	http://bit.ly/1mFEgpT	229	t	t	2014-10-24 15:47:43.639915
238	37	ENSINO MÉDIO – 3.ª Série	\N	\N	t	t	2014-10-24 15:47:43.640517
239	37	DIREITOS HUMANOS E FORMAÇÃO DA SOCIEDADE BRASILEIRA	\N	238	t	t	2014-10-24 15:47:43.641084
240	37	Conceito e significados	http://bit.ly/1dnaYte	239	t	t	2014-10-24 15:47:43.641608
241	37	A Declaração Universal dos Direitos Humanos	http://bit.ly/1dnaYte	239	t	t	2014-10-24 15:47:43.642203
242	37	Direitos e cidadania no Brasil.	http://bit.ly/1dnaYte	239	t	t	2014-10-24 15:47:43.642712
243	37	Minorias sociais e direitos humanos no Brasil (inclusão X exclusão).	http://bit.ly/1dnaYte	239	t	t	2014-10-24 15:47:43.643298
244	37	Relação de gênero e sexualidade (Homofobia, Lei Maria da Penha e outros)	http://bit.ly/1mt68Ob	239	t	t	2014-10-24 15:47:43.643801
245	37	Relações etárias (conflito de gerações, ECA, Estatuto do Idoso)	http://bit.ly/1gWk6A0	239	t	t	2014-10-24 15:47:43.644325
246	37	DIREITOS HUMANOS E SOCIEDADE BRASILEIRA	\N	238	t	t	2014-10-24 15:47:43.644832
247	37	A Declaração Universal dos Direitos Humanos.	http://bit.ly/1dnaYte	246	t	t	2014-10-24 15:47:43.645375
248	37	Minorias sociais (inclusão X exclusão).	http://bit.ly/1dnaYte	246	t	t	2014-10-24 15:47:43.645882
249	37	Relação de gênero e sexualidade (Homofobia, Lei Maria da Penha, Turismo sexual, etc.)	http://bit.ly/1mt68Ob	246	t	t	2014-10-24 15:47:43.646411
250	37	Relações etárias (conflito de gerações, ECA, Estatuto do Idoso	http://bit.ly/1gWkhLD	246	t	t	2014-10-24 15:47:43.646918
251	37	TRABALHO E SOCIEDADE – O TRABALHO NA PERSPECTIVA CONTEMPORÂNEA	\N	238	t	t	2014-10-24 15:47:43.647436
252	37	O trabalho nas diferentes sociedades.	http://bit.ly/1pd9fs9	251	t	t	2014-10-24 15:47:43.647946
253	37	O trabalho na sociedade moderna capitalista	http://bit.ly/1pd9fs9	251	t	t	2014-10-24 15:47:43.648481
254	37	A questão do trabalho no Brasil	http://bit.ly/1pd9fs9	251	t	t	2014-10-24 15:47:43.649158
255	37	Globalização e desemprego	http://bit.ly/1dNoBlw	251	t	t	2014-10-24 15:47:43.64967
256	37	O jovem e o mercado de trabalho.	http://bit.ly/1pd9fs9	251	t	t	2014-10-24 15:47:43.650187
257	37	Sociedades de classes	http://bit.ly/1hVzEoI	251	t	t	2014-10-24 15:47:43.650697
258	37	Ideologia	http://bit.ly/1jDHCoK	251	t	t	2014-10-24 15:47:43.651275
259	37	Modos de produção:	\N	251	t	t	2014-10-24 15:47:43.651783
260	37	Primitiva	\N	259	t	t	2014-10-24 15:47:43.652344
261	37	Escravista	\N	259	t	t	2014-10-24 15:47:43.652847
262	37	Asiática	\N	259	t	t	2014-10-24 15:47:43.65338
263	37	Feudal	http://bit.ly/1mFH0nr	259	t	t	2014-10-24 15:47:43.65389
264	37	Socialista	\N	259	t	t	2014-10-24 15:47:43.65441
265	37	Capitalista.	http://bit.ly/1hVzEoI	259	t	t	2014-10-24 15:47:43.654914
266	37	Karl Marx e o modo de produção capitalista.	http://bit.ly/1hVzEoI	251	t	t	2014-10-24 15:47:43.655485
267	37	A divisão social do trabalho nas diferentes sociedades.	http://bit.ly/1pd9fs9	251	t	t	2014-10-24 15:47:43.656082
268	37	JUVENTUDE E PERSPECTIVAS/ PROJETO DE VIDA	\N	238	t	t	2014-10-24 15:47:43.656593
269	37	Juventude e Identidade.	http://bit.ly/1mtbSYd	268	t	t	2014-10-24 15:47:43.657171
270	37	Juventude e Ensino Médio	http://bit.ly/1mtbSYd	268	t	t	2014-10-24 15:47:43.657681
271	37	Juventude e Formação Profissional	http://bit.ly/1mtbSYd	268	t	t	2014-10-24 15:47:43.658215
272	37	Projeto de Vida na Juventude	http://bit.ly/1mtbSYd	268	t	t	2014-10-24 15:47:43.658733
273	37	Relação de gênero e sexualidade.	http://bit.ly/1mt68Ob	268	t	t	2014-10-24 15:47:43.659291
274	37	CIDADANIA E DIREITO TRABALHISTA NO BRASIL.	\N	238	t	t	2014-10-24 15:47:43.659801
275	37	Trabalho e CLT	http://bit.ly/1pd9fs9	274	t	t	2014-10-24 15:47:43.660372
276	37	A questão do trabalho no Brasil ( Parte 1) :	\N	274	t	t	2014-10-24 15:47:43.660876
277	37	Informal	http://bit.ly/1pd9fs9	276	t	t	2014-10-24 15:47:43.661461
278	37	Temporário	http://bit.ly/1pd9fs9	276	t	t	2014-10-24 15:47:43.662083
279	37	Terceirização da mão de obra.	http://bit.ly/1pd9fs9	276	t	t	2014-10-24 15:47:43.662594
280	37	A questão do trabalho no Brasil (Parte 2) :	\N	274	t	t	2014-10-24 15:47:43.663116
281	37	Escravo	\N	280	t	t	2014-10-24 15:47:43.663621
282	37	Doméstico	\N	280	t	t	2014-10-24 15:47:43.664195
283	37	COMPREENDENDO A SOCIEDADE BAIANA COM A AJUDA DA SOCIOLOGIA.	\N	238	t	t	2014-10-24 15:47:43.664703
284	37	O que é mesmo baianidade?	http://bit.ly/1gVHrSl	283	t	t	2014-10-24 15:47:43.665217
285	37	Identidade coletiva dos jovens baianos (as tribos e outras formas de associações)	http://bit.ly/1gVHrSl	283	t	t	2014-10-24 15:47:43.665724
286	37	Sincretismo religioso/ Intolerância Religiosa	http://bit.ly/1mt7458	283	t	t	2014-10-24 15:47:43.666285
287	37	RELAÇÕES SOCIAIS E ESPACIAIS (CAMPO/CIDADE)	\N	238	t	t	2014-10-24 15:47:43.666797
288	37	Industrialização	http://bit.ly/1gWllz7	287	t	t	2014-10-24 15:47:43.667355
289	37	urbanização	http://bit.ly/1mt7Hvt	287	t	t	2014-10-24 15:47:43.667861
290	37	Globalização e sociedade em rede	http://bit.ly/1gWmOFt	287	t	t	2014-10-24 15:47:43.668409
291	37	Tribos urbanas	http://bit.ly/1gVHrSl	287	t	t	2014-10-24 15:47:43.66892
292	37	Redes Sociais: fome, miséria e violência (campo x cidade)	http://bit.ly/1gWqPcY	287	t	t	2014-10-24 15:47:43.669396
1357	38	ENSINO MÉDIO – 1ª série	\N	\N	t	t	2014-10-24 15:47:43.669728
1358	38	Como atribuir sentido aos textos verbais e não-verbais	http://bit.ly/1wmDmAw	1357	t	t	2014-10-24 15:47:43.670213
1359	38	A linguagem:	http://bit.ly/1h0uStc	1357	t	t	2014-10-24 15:47:43.670606
1360	38	Socialização e enunciação	\N	1359	t	t	2014-10-24 15:47:43.670937
1361	38	Linguagem	http://bit.ly/1fS58e8	1359	t	t	2014-10-24 15:47:43.671286
1362	38	Língua e fala	http://bit.ly/1sNiVNR	1359	t	t	2014-10-24 15:47:43.677599
1363	38	Escrita e oralidade	http://bit.ly/1odl3Nz	1359	t	t	2014-10-24 15:47:43.677934
1364	38	Conversação	\N	1359	t	t	2014-10-24 15:47:43.678359
1365	38	O processo de comunicação e seus elementos	http://bit.ly/1jB40Nr	1357	t	t	2014-10-24 15:47:43.67869
1366	38	Gêneros e tipos textuais no cotidiano:	http://bit.ly/1gIcvct	1357	t	t	2014-10-24 15:47:43.679114
1367	38	Notícias	http://bit.ly/RaaNqy	1366	t	t	2014-10-24 15:47:43.67953
1368	38	Entrevista	http://bit.ly/1pd29lP	1366	t	t	2014-10-24 15:47:43.679865
1369	38	Carta do leitor	\N	1366	t	t	2014-10-24 15:47:43.680311
1370	38	Funções da linguagem	http://bit.ly/1h0uStc	1357	t	t	2014-10-24 15:47:43.680696
1371	38	Os tipos de discurso (discurso direto e indireto)	\N	1357	t	t	2014-10-24 15:47:43.681171
1372	38	A prática de leitura e a construção de sentidos	http://bit.ly/1wmDmAw	1357	t	t	2014-10-24 15:47:43.681506
1373	38	Como atribuir sentido aos textos verbais e não-verbais	http://bit.ly/1wmDmAw	1357	t	t	2014-10-24 15:47:43.681839
1374	38	Textualidade	http://bit.ly/1lZlx5B	1357	t	t	2014-10-24 15:47:43.682201
1375	38	Situcionalidade	\N	1357	t	t	2014-10-24 15:47:43.682626
1376	38	Intencionalidade	\N	1357	t	t	2014-10-24 15:47:43.683151
1377	38	Aceitabilidade	\N	1357	t	t	2014-10-24 15:47:43.683488
1378	38	intertextualidade	http://bit.ly/1perNIA	1357	t	t	2014-10-24 15:47:43.683822
1379	38	Reconhecimento dos gêneros e tipos textuais na leitura	\N	1357	t	t	2014-10-24 15:47:43.684233
1380	38	Estratégias de leitura: inferência, localização de informações, antecipação, pressuposição, etc	\N	1357	t	t	2014-10-24 15:47:43.684575
1381	38	A sonoridade das palavras	\N	1357	t	t	2014-10-24 15:47:43.685106
1382	38	Aspectos formais	\N	1357	t	t	2014-10-24 15:47:43.685452
1383	38	discursivos	\N	1357	t	t	2014-10-24 15:47:43.685784
1384	38	semânticos	\N	1357	t	t	2014-10-24 15:47:43.686156
1385	38	lexicais	http://bit.ly/1fFuqSY	1357	t	t	2014-10-24 15:47:43.686493
1386	38	sintáticos	\N	1357	t	t	2014-10-24 15:47:43.68692
1387	38	morfossintáticos	\N	1357	t	t	2014-10-24 15:47:43.687369
1388	38	Textos em prosa e verso	http://bit.ly/1sDLI8p	1357	t	t	2014-10-24 15:47:43.687697
1389	38	Da análise da forma à construção de sentido	\N	1357	t	t	2014-10-24 15:47:43.688104
1390	38	Os sons e suas representações gráficas	\N	1357	t	t	2014-10-24 15:47:43.688446
1391	38	As palavras, suas entonações e grafias	\N	1357	t	t	2014-10-24 15:47:43.688788
1392	38	A gramática da frase	\N	1357	t	t	2014-10-24 15:47:43.689336
1393	38	A gramática do texto	\N	1357	t	t	2014-10-24 15:47:43.689844
1394	38	Os constituintes básicos da oração	\N	1357	t	t	2014-10-24 15:47:43.690359
1395	38	As relações sintáticas dentro do sintagma nominal e verbal	\N	1357	t	t	2014-10-24 15:47:43.690861
1396	38	A coordenação e os efeitos de sentido	\N	1357	t	t	2014-10-24 15:47:43.69139
1397	38	Língua e Literatura: múltiplas linguagens	\N	1357	t	t	2014-10-24 15:47:43.691897
1398	38	A arte literária	\N	1397	t	t	2014-10-24 15:47:43.692444
1399	38	Os gêneros literários	\N	1397	t	t	2014-10-24 15:47:43.692948
1400	38	Os estilos de época	http://bit.ly/1w4roie	1397	t	t	2014-10-24 15:47:43.693509
1401	38	O uso literário das tradições populares	\N	1397	t	t	2014-10-24 15:47:43.694121
1402	38	Leituras cinematográficas	http://bit.ly/1vXJqmn	1397	t	t	2014-10-24 15:47:43.694637
1403	38	Leituras fotográficas	http://bit.ly/1vXK8js	1397	t	t	2014-10-24 15:47:43.695165
1404	38	Leituras da cidade	\N	1397	t	t	2014-10-24 15:47:43.695673
1405	38	Leituras musicais	http://bit.ly/1wkpQgP	1397	t	t	2014-10-24 15:47:43.696209
1406	38	Análise linguística e reescrita textual	\N	1357	t	t	2014-10-24 15:47:43.696712
1407	38	Colocação pronominal	\N	1406	t	t	2014-10-24 15:47:43.697263
1408	38	Crase	http://bit.ly/1w4ss5G	1406	t	t	2014-10-24 15:47:43.697773
1409	38	Pontuação	\N	1406	t	t	2014-10-24 15:47:43.698326
1410	38	Emprego de conjunções e preposições (coesão)	\N	1406	t	t	2014-10-24 15:47:43.698832
1411	38	Coerência	http://bit.ly/1wkqjj5	1406	t	t	2014-10-24 15:47:43.69935
1412	38	Retextualização coletiva	\N	1406	t	t	2014-10-24 15:47:43.699855
1413	38	Retextualização em grupo	\N	1406	t	t	2014-10-24 15:47:43.700413
1414	38	Retextualização individual	\N	1406	t	t	2014-10-24 15:47:43.700928
1415	38	Produção textual oral e escrita	\N	1357	t	t	2014-10-24 15:47:43.701466
1416	38	A organização de um texto	\N	1415	t	t	2014-10-24 15:47:43.702011
1417	38	A hierarquia das ideias no texto	\N	1415	t	t	2014-10-24 15:47:43.702548
1418	38	O parágrafo e os tipos de parágrafos	http://bit.ly/1wmCOL0	1415	t	t	2014-10-24 15:47:43.703077
1419	38	A progressão textual	\N	1415	t	t	2014-10-24 15:47:43.70359
1420	38	O léxico	http://bit.ly/1vXz6L3	1415	t	t	2014-10-24 15:47:43.70417
1421	38	Mecanismos de coesão (gramatical e semântica)	http://bit.ly/1vXMNtx	1415	t	t	2014-10-24 15:47:43.704679
1422	38	Coerência textual	http://bit.ly/1vXMX41	1415	t	t	2014-10-24 15:47:43.705194
1423	38	Textos orais	\N	1415	t	t	2014-10-24 15:47:43.7057
1424	38	Recursos estilísticos – a sonoridade das palavras	\N	1415	t	t	2014-10-24 15:47:43.706212
1425	38	Redes sociais e as construções de sentido – as linguagens dos Blogs, Facebook, Orkut, torpedos	\N	1415	t	t	2014-10-24 15:47:43.706724
1426	38	(SMS), clipes	\N	1415	t	t	2014-10-24 15:47:43.707294
1427	38	 A internet e os processos de comunicação	http://bit.ly/1wkqNWf	1415	t	t	2014-10-24 15:47:43.7078
1428	38	O hipertexto	http://bit.ly/1vXOffr	1415	t	t	2014-10-24 15:47:43.708404
1430	38	ENSINO MÉDIO – 2ª série	\N	\N	t	t	2014-10-24 15:47:43.708913
1431	38	A Língua Portuguesa e as práticas discursivas	\N	1430	t	t	2014-10-24 15:47:43.709527
1432	38	Gêneros e tipos textuais no cotidiano:	http://bit.ly/1wkiaek	1431	t	t	2014-10-24 15:47:43.710142
1433	38	Notícias	http://bit.ly/1vXm8gj	1431	t	t	2014-10-24 15:47:43.710648
1434	38	Entrevista	http://bit.ly/1vXntDU	1431	t	t	2014-10-24 15:47:43.711217
1435	38	Carta do leitor	http://bit.ly/1vXpCzt	1431	t	t	2014-10-24 15:47:43.711723
1436	38	Os tipos de discurso	\N	1431	t	t	2014-10-24 15:47:43.71228
1437	38	A prática de leitura e a construção de sentidos	\N	1430	t	t	2014-10-24 15:47:43.712791
1438	38	Como atribuir sentido aos textos verbais e não verbais	\N	1437	t	t	2014-10-24 15:47:43.713311
1439	38	Textualidade	\N	1437	t	t	2014-10-24 15:47:43.713814
1440	38	Situcionalidade	\N	1437	t	t	2014-10-24 15:47:43.714375
1441	38	Intencionalidade	\N	1437	t	t	2014-10-24 15:47:43.714883
1442	38	Aceitabilidade	\N	1437	t	t	2014-10-24 15:47:43.715415
1443	38	Intertextualidade	http://bit.ly/1vXs7Sp	1437	t	t	2014-10-24 15:47:43.715925
1444	38	Estratégias de leitura: inferência, localização de informações, antecipação, pressuposição, etc.	\N	1437	t	t	2014-10-24 15:47:43.716492
1445	38	Aspectos formais	\N	1437	t	t	2014-10-24 15:47:43.717161
1446	38	Discursivos	http://bit.ly/1wkjIFm	1437	t	t	2014-10-24 15:47:43.717786
1447	38	Semânticos	http://bit.ly/1wkmrhU	1437	t	t	2014-10-24 15:47:43.718354
1448	38	Lexicais	http://bit.ly/1vXz6L3	1437	t	t	2014-10-24 15:47:43.718867
1449	38	S e morfossintáticos	http://bit.ly/1vXz6L3	1437	t	t	2014-10-24 15:47:43.719413
1450	38	Textos em prosa e verso	http://bit.ly/1vXzKZ1	1437	t	t	2014-10-24 15:47:43.719917
1451	38	Da análise da forma à construção de sentido	\N	1430	t	t	2014-10-24 15:47:43.720477
1452	38	As relações sintáticas dentro do sintagma nominal e verbal	\N	1451	t	t	2014-10-24 15:47:43.721079
1453	38	Operadores argumentativos	\N	1451	t	t	2014-10-24 15:47:43.721587
1454	38	A subordinação e os efeitos de sentido no texto	http://bit.ly/1wkoqmw	1451	t	t	2014-10-24 15:47:43.722196
1455	38	Níveis de formalidade	\N	1451	t	t	2014-10-24 15:47:43.722703
1456	38	Variações linguísticas	http://bit.ly/1vXGaaz	1451	t	t	2014-10-24 15:47:43.723217
1457	38	Língua e Literatura: múltiplas linguagens	\N	1430	t	t	2014-10-24 15:47:43.72372
1458	38	Tendências da literatura brasileira contemporânea	http://bit.ly/1vXHyd4	1458	t	t	2014-10-24 15:47:43.724288
1459	38	Poesia	http://bit.ly/1vXHSsm	1458	t	t	2014-10-24 15:47:43.724804
1460	38	Prosa	http://bit.ly/1vXIfTQ	1458	t	t	2014-10-24 15:47:43.72536
1461	38	Contribuições das culturas africanas e indígenas à cultura brasileira	http://bit.ly/1wkpjLT	1458	t	t	2014-10-24 15:47:43.725878
1462	38	Leituras cinematográficas	http://bit.ly/1vXJqmn	1458	t	t	2014-10-24 15:47:43.726413
1463	38	Leituras fotográficas	http://bit.ly/1vXK8js	1458	t	t	2014-10-24 15:47:43.726919
1598	38	Análise linguística e reescrita textual	\N	1430	t	t	2014-10-24 15:47:43.727486
1599	38	Concordância verbal	http://bit.ly/1wkpYNk	1598	t	t	2014-10-24 15:47:43.728153
1600	38	Concordância nominal	\N	1598	t	t	2014-10-24 15:47:43.728662
1601	38	Regência verbal	http://bit.ly/1vXLQkL	1598	t	t	2014-10-24 15:47:43.729284
1602	38	Regência nominal	\N	1598	t	t	2014-10-24 15:47:43.72979
1603	38	Coerência	http://bit.ly/1wkqjj5	1598	t	t	2014-10-24 15:47:43.730358
1604	38	Retextualização coletiva	\N	1598	t	t	2014-10-24 15:47:43.730872
1605	38	Retextualização em grupo	\N	1598	t	t	2014-10-24 15:47:43.731408
1606	38	Retextualização individual	\N	1598	t	t	2014-10-24 15:47:43.731913
1607	38	Produção textual: oral e escrita	\N	1430	t	t	2014-10-24 15:47:43.732476
1608	38	A progressão textual	\N	1607	t	t	2014-10-24 15:47:43.733085
1609	38	O léxico	http://bit.ly/1vXz6L3	1607	t	t	2014-10-24 15:47:43.733593
1610	38	Mecanismos de coesão (gramatical e semântica)	http://bit.ly/1vXMNtx	1607	t	t	2014-10-24 15:47:43.73416
1611	38	Coerência textual	http://bit.ly/1vXMX41	1607	t	t	2014-10-24 15:47:43.734672
1612	38	Textos orais	http://bit.ly/1vXMX41	1607	t	t	2014-10-24 15:47:43.735213
1613	38	Redes sociais e as construções de sentido – as linguagens dos blogs, facebook, twiter, torpedos (SMS), clipes	\N	1607	t	t	2014-10-24 15:47:43.735719
1614	38	 A internet e os processos de comunicação	http://bit.ly/1wkqNWf	1607	t	t	2014-10-24 15:47:43.73629
1615	38	O hipertexto	http://bit.ly/1vXOffr	1607	t	t	2014-10-24 15:47:43.736803
1617	38	A Língua Portuguesa e as práticas discursivas	\N	1616	t	t	2014-10-24 15:47:43.737356
1618	38	Gêneros e tipos textuais no cotidiano:	http://bit.ly/1wkiaek	1617	t	t	2014-10-24 15:47:43.737863
1619	38	Notícias	http://bit.ly/1vXm8gj	1617	t	t	2014-10-24 15:47:43.738418
1620	38	Entrevista	http://bit.ly/1vXntDU	1617	t	t	2014-10-24 15:47:43.738941
1621	38	Carta do leitor	http://bit.ly/1vXpCzt	1617	t	t	2014-10-24 15:47:43.739523
1622	38	Os tipos de discurso	\N	1617	t	t	2014-10-24 15:47:43.740083
1623	38	A prática de leitura e a construção de sentidos	\N	1616	t	t	2014-10-24 15:47:43.740599
1624	38	Textualidade	\N	1623	t	t	2014-10-24 15:47:43.74117
1625	38	Situcionalidade	\N	1623	t	t	2014-10-24 15:47:43.741675
1626	38	Intencionalidade	\N	1623	t	t	2014-10-24 15:47:43.742215
1627	38	Aceitabilidade	\N	1623	t	t	2014-10-24 15:47:43.742726
1628	38	Intertextualidade	http://bit.ly/1vXs7Sp	1623	t	t	2014-10-24 15:47:43.743277
1629	38	Estratégias de leitura: inferência, localização de informações, antecipação, pressuposição, etc.	\N	1623	t	t	2014-10-24 15:47:43.743784
1630	38	Aspectos formais	http://bit.ly/1vXs7Sp	1623	t	t	2014-10-24 15:47:43.744378
1631	38	Discursivos	http://bit.ly/1wkjIFm	1630	t	t	2014-10-24 15:47:43.744881
1632	38	Semânticos	http://bit.ly/1wkmrhU	1630	t	t	2014-10-24 15:47:43.745415
1633	38	Lexicais	http://bit.ly/1vXz6L3	1630	t	t	2014-10-24 15:47:43.74592
1634	38	Sintáticos e morfossintáticos	http://bit.ly/1wkmGJT	1630	t	t	2014-10-24 15:47:43.746444
1635	38	Textos em:	http://bit.ly/1vXzKZ1	1623	t	t	2014-10-24 15:47:43.746953
1636	38	Prosa	http://bit.ly/1vXzKZ1	1635	t	t	2014-10-24 15:47:43.747493
1637	38	Verso	http://bit.ly/1wknuOO	1635	t	t	2014-10-24 15:47:43.748158
1638	38	Da análise da forma à construção de sentido	\N	1616	t	t	2014-10-24 15:47:43.74867
1639	38	As relações sintáticas dentro do sintagma nominal e verbal	\N	1638	t	t	2014-10-24 15:47:43.749281
1640	38	Operadores argumentativos	\N	1638	t	t	2014-10-24 15:47:43.749804
1641	38	Níveis de formalidade	\N	1638	t	t	2014-10-24 15:47:43.750367
1642	38	Variações linguísticas	http://bit.ly/1vXGaaz	1638	t	t	2014-10-24 15:47:43.750872
1643	38	Língua e Literatura: múltiplas linguagens	\N	1616	t	t	2014-10-24 15:47:43.751409
1644	38	Tendências da literatura brasileira contemporânea	http://bit.ly/1vXHyd4	1643	t	t	2014-10-24 15:47:43.75192
1645	38	Poesia	http://bit.ly/1vXHSsm	1643	t	t	2014-10-24 15:47:43.752443
1646	38	Prosa	http://bit.ly/1vXIfTQ	1643	t	t	2014-10-24 15:47:43.753075
1647	38	Contribuições das culturas à cultura brasileira	\N	1643	t	t	2014-10-24 15:47:43.753581
1648	38	Africanas	http://bit.ly/1wkpjLT	1643	t	t	2014-10-24 15:47:43.754165
1649	38	Indígenas	http://bit.ly/1vXJ4Mj	1643	t	t	2014-10-24 15:47:43.754672
1650	38	Leituras cinematográficas	http://bit.ly/1vXJqmn	1643	t	t	2014-10-24 15:47:43.755209
1651	38	Leituras fotográficas	http://bit.ly/1vXK8js	1643	t	t	2014-10-24 15:47:43.755718
1652	38	Leituras da cidade	\N	1643	t	t	2014-10-24 15:47:43.756286
1653	38	Leituras musicais	http://bit.ly/1wkpQgP	1643	t	t	2014-10-24 15:47:43.756796
1654	38	Análise linguística e reescrita textual	\N	1616	t	t	2014-10-24 15:47:43.757362
1655	38	Concordância verbal	http://bit.ly/1wkpYNk	1654	t	t	2014-10-24 15:47:43.757871
1656	38	Concordância nominal	\N	1654	t	t	2014-10-24 15:47:43.758415
1657	38	Regência verbal	http://bit.ly/1vXLQkL	1654	t	t	2014-10-24 15:47:43.758923
1658	38	Regência nominal	\N	1654	t	t	2014-10-24 15:47:43.759484
1659	38	Coerência	http://bit.ly/1wkqjj5	1654	t	t	2014-10-24 15:47:43.760115
1660	38	Retextualização coletiva	\N	1654	t	t	2014-10-24 15:47:43.760644
1661	38	Retextualização em grupo	\N	1654	t	t	2014-10-24 15:47:43.761204
1662	38	Retextualização individual	\N	1654	t	t	2014-10-24 15:47:43.761713
1663	38	Produção textual: oral e escrita	\N	1616	t	t	2014-10-24 15:47:43.76223
1664	38	A progressão textual	\N	1663	t	t	2014-10-24 15:47:43.762735
1665	38	O léxico	http://bit.ly/1vXz6L3	1663	t	t	2014-10-24 15:47:43.76328
1666	38	Mecanismos de coesão (gramatical e semântica)	http://bit.ly/1vXMNtx	1663	t	t	2014-10-24 15:47:43.763788
1667	38	Coerência textual	http://bit.ly/1vXMX41	1663	t	t	2014-10-24 15:47:43.76431
1668	38	Textos orais	\N	1663	t	t	2014-10-24 15:47:43.764818
1669	38	Tendências atuais na produção de textos para exames	http://bit.ly/1rd92tw	1663	t	t	2014-10-24 15:47:43.765355
1670	38	Redes sociais e as construções de sentido – as linguagens dos Blogs, Facebook, Twitter, torpedos (SMS), clipes	\N	1663	t	t	2014-10-24 15:47:43.765863
1671	38	 A internet e os processos de comunicação	http://bit.ly/1wkqNWf	1663	t	t	2014-10-24 15:47:43.766414
1672	38	O hipertexto	http://bit.ly/1vXOffr	1663	t	t	2014-10-24 15:47:43.766917
858	39	ENSINO MÉDIO – 1.ª Série	\N	\N	t	t	2014-10-24 15:47:43.767439
859	39	A Ciência Química, tecnologia e sociedade	\N	858	t	t	2014-10-24 15:47:43.767927
860	39	Ciência Química	http://bit.ly/1gGtHRj	859	t	t	2014-10-24 15:47:43.768473
861	39	Tecnologia	http://bit.ly/1gGtTzR	859	t	t	2014-10-24 15:47:43.769149
862	39	Sociedade	http://bit.ly/1gGu26l	859	t	t	2014-10-24 15:47:43.769661
863	39	Sistemas materiais:	\N	858	t	t	2014-10-24 15:47:43.770211
864	39	Matéria, propriedades da matéria	http://bit.ly/1gGu4LA	863	t	t	2014-10-24 15:47:43.770736
865	39	Fenômenos físicos e fenômenos químicos	http://bit.ly/1pd7dIy	863	t	t	2014-10-24 15:47:43.771283
866	39	Estados físicos e mudanças de estados físicos	http://bit.ly/1gGukdB	863	t	t	2014-10-24 15:47:43.77179
867	39	Substâncias puras e misturas, sistemas homogêneos e heterogêneos	http://bit.ly/1gGuqSt	858	t	t	2014-10-24 15:47:43.772354
868	39	Análise imediata: separação dos componentes das misturas heterogêneas e homogêneas.	http://bit.ly/1gGuqSt	867	t	t	2014-10-24 15:47:43.77286
869	39	Conceitos básicos:	\N	858	t	t	2014-10-24 15:47:43.773419
870	39	Átomo	http://bit.ly/1pdaMPc	869	t	t	2014-10-24 15:47:43.773928
871	39	Elemento químico	http://bit.ly/1u1C1MH	869	t	t	2014-10-24 15:47:43.77451
872	39	Íons	http://bit.ly/1u1CyOO	869	t	t	2014-10-24 15:47:43.775152
873	39	Moléculas	http://bit.ly/1pd52oo	869	t	t	2014-10-24 15:47:43.775661
874	39	Estrutura atômica:	\N	858	t	t	2014-10-24 15:47:43.776202
875	39	Teorias e modelos atômicos	http://bit.ly/1gGxmP5	874	t	t	2014-10-24 15:47:43.776708
876	39	Classificação periódica:	http://bit.ly/1gGxwFU	858	t	t	2014-10-24 15:47:43.777291
877	39	Histórico	\N	876	t	t	2014-10-24 15:47:43.777803
878	39	Tabela periódica moderna dos elementos químicos	http://bit.ly/1pdbWtY	876	t	t	2014-10-24 15:47:43.778408
879	39	Propriedades periódicas	http://bit.ly/1u1D5A8	876	t	t	2014-10-24 15:47:43.778915
880	39	Ligações químicas	http://bit.ly/1gGxIoR	858	t	t	2014-10-24 15:47:43.779438
881	39	Polaridade da ligação e da molécula	http://bit.ly/1pdccJn	858	t	t	2014-10-24 15:47:43.780157
882	39	Geometria molecular, características dos compostos moleculares;	http://bit.ly/1gGxQoj	858	t	t	2014-10-24 15:47:43.780684
883	39	Ligação metálica, características dos compostos metálicos.	http://bit.ly/1EUSjzI	858	t	t	2014-10-24 15:47:43.78121
884	39	Interações intermoleculares e propriedades da matéria	http://bit.ly/1pdcBvs	858	t	t	2014-10-24 15:47:43.78172
885	39	Número de oxidação	http://bit.ly/1pddO5T	858	t	t	2014-10-24 15:47:43.78224
886	39	Substâncias inorgânicas:	http://bit.ly/1gGySR0	858	t	t	2014-10-24 15:47:43.78275
887	39	ácidos	http://ambiente.educacao.ba.gov.br/conteudos-digitais/conteudo/exibir/id/1318	886	t	t	2014-10-24 15:47:43.783289
888	39	bases	http://ambiente.educacao.ba.gov.br/conteudos-digitais/conteudo/exibir/id/1325	886	t	t	2014-10-24 15:47:43.7838
889	39	sais	lhttp://ambiente.educacao.ba.gov.br/conteudos-digitais/conteudo/exibir/id/1325íngua%20Portuguesa_Matriz.docx	886	t	t	2014-10-24 15:47:43.784355
890	39	óxidos	http://bit.ly/1u1DQt7	886	t	t	2014-10-24 15:47:43.784862
891	39	Reações químicas	http://bit.ly/1pde44Z	858	t	t	2014-10-24 15:47:43.78542
892	39	Balanceamento	http://bit.ly/1pdegRx	891	t	t	2014-10-24 15:47:43.78593
893	39	Equações químicas	http://bit.ly/1gGzaHz	891	t	t	2014-10-24 15:47:43.786474
894	39	ENSINO MÉDIO – 2.ª Série	\N	\N	t	t	2014-10-24 15:47:43.787075
895	39	Cálculos químicos:	\N	894	t	t	2014-10-24 15:47:43.787569
896	39	Massa atômica	http://bit.ly/1pdeuID	895	t	t	2014-10-24 15:47:43.788179
897	39	Molecular e molar	http://bit.ly/1pdeuID	895	t	t	2014-10-24 15:47:43.788689
898	39	Quantidade de matéria	http://bit.ly/1gGzo1o	895	t	t	2014-10-24 15:47:43.789376
899	39	Constante de Avogadro	http://bit.ly/1gGzo1o	895	t	t	2014-10-24 15:47:43.789901
900	39	Cálculo estequiométrico	http://bit.ly/1pdf1u5	895	t	t	2014-10-24 15:47:43.790414
901	39	Comportamento físico dos gases: transformações envolvendo massa fixa de gás	http://bit.ly/1gGzC8R	894	t	t	2014-10-24 15:47:43.790923
902	39	Lei do Gás Ideal	http://ambiente.educacao.ba.gov.br/conteudos-digitais/conteudo/exibir/id/2295	901	t	t	2014-10-24 15:47:43.791519
903	39	Misturas gasosas, densidade dos gases, difusão e efusão dos gases	\N	901	t	t	2014-10-24 15:47:43.792152
904	39	Teoria cinética dos gases	http://bit.ly/1u261bt	901	t	t	2014-10-24 15:47:43.792659
905	39	Soluções:	http://bit.ly/1pdfjkt	894	t	t	2014-10-24 15:47:43.793228
906	39	Classificação	\N	905	t	t	2014-10-24 15:47:43.793736
907	39	Solubilidade	http://bit.ly/1gGzNRz	905	t	t	2014-10-24 15:47:43.794289
908	39	Expressões de concentrações, relação entre os diversos tipos de concentração	http://bit.ly/1gGzSou	905	t	t	2014-10-24 15:47:43.794795
909	39	Diluição e mistura de soluções	\N	905	t	t	2014-10-24 15:47:43.795354
910	39	Propriedades coligativas:	http://bit.ly/1gGzXbI	894	t	t	2014-10-24 15:47:43.795904
911	39	Características e aplicações da Tonoscopia	\N	910	t	t	2014-10-24 15:47:43.796485
912	39	Ebulioscopia	\N	910	t	t	2014-10-24 15:47:43.797118
913	39	Crioscopia	http://ambiente.educacao.ba.gov.br/conteudos-digitais/conteudo/exibir/id/1121	910	t	t	2014-10-24 15:47:43.79763
914	39	Osmoscopia	\N	910	t	t	2014-10-24 15:47:43.798189
915	39	Termoquímica:	http://bit.ly/1pdfZ9w	894	t	t	2014-10-24 15:47:43.798693
916	39	Calor e fenômenos da matéria	\N	915	t	t	2014-10-24 15:47:43.799216
917	39	Calorimetria	\N	915	t	t	2014-10-24 15:47:43.799721
918	39	Entalpia	http://bit.ly/1u2aH0U	915	t	t	2014-10-24 15:47:43.800321
919	39	Equação termoquímica	http://bit.ly/1u2aH0U	915	t	t	2014-10-24 15:47:43.800851
920	39	Lei de Hess	http://bit.ly/1EVmuql	915	t	t	2014-10-24 15:47:43.801379
921	39	Energia de ligação	http://bit.ly/1EVmJ4P	915	t	t	2014-10-24 15:47:43.801886
922	39	Entropia	\N	915	t	t	2014-10-24 15:47:43.802416
923	39	Cinética química	http://bit.ly/1pdg7WH	894	t	t	2014-10-24 15:47:43.802925
924	39	Equilíbrios químicos	http://bit.ly/1gGAgDo	894	t	t	2014-10-24 15:47:43.80348
925	39	processos reversíveis e irreversíveis	http://ambiente.educacao.ba.gov.br/conteudos-digitais/conteudo/exibir/id/2549	924	t	t	2014-10-24 15:47:43.804012
926	39	Caracterização do sistema	\N	924	t	t	2014-10-24 15:47:43.804579
927	39	Fatores que interferem no equilíbrio	\N	924	t	t	2014-10-24 15:47:43.805167
928	39	Princípio de Le Châtelier	http://ambiente.educacao.ba.gov.br/conteudos-digitais/conteudo/exibir/id/2552	894	t	t	2014-10-24 15:47:43.805678
929	39	Aspectos quantitativos e constante de equilíbrio	\N	928	t	t	2014-10-24 15:47:43.806212
930	39	Equilíbrio iônico da água	\N	928	t	t	2014-10-24 15:47:43.806722
931	39	Constante de equilíbrio de solubilidade	\N	928	t	t	2014-10-24 15:47:43.807292
932	39	ENSINO MÉDIO – 3.ª Série	\N	\N	t	t	2014-10-24 15:47:43.807798
933	39	Química orgânica:	http://bit.ly/1EVnb35	932	t	t	2014-10-24 15:47:43.80835
934	39	Importância dos compostos de carbono	http://bit.ly/1pdglwS	933	t	t	2014-10-24 15:47:43.80886
935	39	Estudo do carbono	http://bit.ly/1pdgr7Q	933	t	t	2014-10-24 15:47:43.80942
936	39	Identificação e classificação das cadeias carbônicas	http://bit.ly/1pdgFvK	933	t	t	2014-10-24 15:47:43.809931
937	39	Estudo dos Hidrocarbonetos:	http://bit.ly/1gGAPx5	933	t	t	2014-10-24 15:47:43.810454
938	39	Classificação	\N	937	t	t	2014-10-24 15:47:43.811034
939	39	Nomenclatura	http://bit.ly/1gGB2jz	937	t	t	2014-10-24 15:47:43.811559
940	39	Principais aplicações dos compostos de uso cotidiano	\N	937	t	t	2014-10-24 15:47:43.812164
941	39	Petróleo, meio ambiente e sociedade.	http://bit.ly/1gGBsq9	932	t	t	2014-10-24 15:47:43.812675
942	39	Funções orgânicas	http://bit.ly/1pdhzs7	932	t	t	2014-10-24 15:47:43.813195
943	39	Compostos multifuncionais	http://bit.ly/1pdhzs7	932	t	t	2014-10-24 15:47:43.813705
944	39	Isomeria	http://bit.ly/1pdiA3t	932	t	t	2014-10-24 15:47:43.814216
945	39	Reações orgânicas	http://bit.ly/1gGBKNZ	932	t	t	2014-10-24 15:47:43.814724
946	39	Polímeros e sociedade	http://bit.ly/1pdiOrg	932	t	t	2014-10-24 15:47:43.81524
947	39	Eletroquímica:	http://bit.ly/1gGBUoB	932	t	t	2014-10-24 15:47:43.815744
948	39	Oxi - redução	\N	947	t	t	2014-10-24 15:47:43.816283
949	39	Potencial eletroquímico	http://ambiente.educacao.ba.gov.br/conteudos-digitais/conteudo/exibir/id/1118	947	t	t	2014-10-24 15:47:43.816795
950	39	Pilhas	http://bit.ly/1pdj7SK	947	t	t	2014-10-24 15:47:43.81736
951	39	Baterias	http://ambiente.educacao.ba.gov.br/conteudos-digitais/conteudo/exibir/id/1966	947	t	t	2014-10-24 15:47:43.817867
952	39	Eletrólise	\N	947	t	t	2014-10-24 15:47:43.818415
953	39	Radioatividade:	http://bit.ly/1pdjdKh	932	t	t	2014-10-24 15:47:43.818922
954	39	Leis da radioatividade	\N	953	t	t	2014-10-24 15:47:43.819441
955	39	Decaimento radioativo	http://ambiente.educacao.ba.gov.br/conteudos-digitais/conteudo/exibir/id/157	953	t	t	2014-10-24 15:47:43.819948
956	39	Fissão e fusão nuclear	http://bit.ly/1u2cvqN	953	t	t	2014-10-24 15:47:43.820501
1795	40	ENSINO MÉDIO – 1.ª Série	\N	\N	t	t	2014-10-24 15:47:43.821152
1796	40	Geografia uma ciência em campo	\N	1795	t	t	2014-10-24 15:47:43.821663
1797	40	Objeto de estudo da geografia, importância e finalidade	http://bit.ly/YHSjSv	1796	t	t	2014-10-24 15:47:43.822217
1798	40	Espaço Geográfico	http://bit.ly/1pcSj3p	1796	t	t	2014-10-24 15:47:43.822744
1799	40	Paisagens	http://bit.ly/1qS3y0Q	1796	t	t	2014-10-24 15:47:43.8233
1800	40	Natureza	http://bit.ly/1h7CPi7	1799	t	t	2014-10-24 15:47:43.823805
1801	40	Memórias	\N	1799	t	t	2014-10-24 15:47:43.824367
1802	40	Paisagens geográficas	http://bit.ly/1pcSj3p	1799	t	t	2014-10-24 15:47:43.82488
1803	40	Estrutura e dinâmica dos diferentes espaços: urbano e rural	http://bit.ly/1inGKoX	1799	t	t	2014-10-24 15:47:43.825395
1804	40	Cartografia	\N	1795	t	t	2014-10-24 15:47:43.825901
1805	40	Interpretação, orientação e localização do espaço (formação do espaço geográfico)	http://bit.ly/1gcnUCA	1804	t	t	2014-10-24 15:47:43.826427
1806	40	Coordenadas Geográficas	http://bit.ly/1o0NSxd	1804	t	t	2014-10-24 15:47:43.826935
1807	40	Tecnologias da Cartografia GPS	http://bit.ly/1o0NSxd	1804	t	t	2014-10-24 15:47:43.827476
1808	40	Escala	http://bit.ly/1gFzRAZ	1804	t	t	2014-10-24 15:47:43.828015
1809	40	Dinâmica climática	\N	1795	t	t	2014-10-24 15:47:43.828553
1810	40	Movimentos da Terra e suas conseqüências	http://bit.ly/1d3Dn7i	1795	t	t	2014-10-24 15:47:43.829182
1811	40	Fuso horário	http://bit.ly/1qSAHJQ	1810	t	t	2014-10-24 15:47:43.82969
1812	40	Clima: Brasil e Mundo	\N	1795	t	t	2014-10-24 15:47:43.830208
1813	40	Tempo e clima	http://bit.ly/1fYPGxR	1812	t	t	2014-10-24 15:47:43.83072
1814	40	Fatores climáticos	http://bit.ly/1dp4Sc4	1812	t	t	2014-10-24 15:47:43.831324
1815	40	Elementos do clima	http://bit.ly/1fYPGxR	1812	t	t	2014-10-24 15:47:43.831827
1816	40	Tipos de clima;	http://bit.ly/1hZAj9Q	1812	t	t	2014-10-24 15:47:43.832365
1817	40	Climas no Brasil	\N	1812	t	t	2014-10-24 15:47:43.832869
1818	40	Os fenômenos climáticos e a interferência humana	http://bit.ly/1dmfckN	1812	t	t	2014-10-24 15:47:43.833413
1819	40	Mudanças Climáticas	http://bit.ly/1nHnCHh	1812	t	t	2014-10-24 15:47:43.83392
1820	40	As formações naturais e a questão ambiental	\N	1795	t	t	2014-10-24 15:47:43.834446
1821	40	Biomas e formações vegetais:	http://bit.ly/1iChNWV	1820	t	t	2014-10-24 15:47:43.834953
1822	40	Principais características das formações vegetais	http://bit.ly/1gImNJv	1820	t	t	2014-10-24 15:47:43.835485
1823	40	Biomas brasileiros	http://bit.ly/1qSqrkY	1820	t	t	2014-10-24 15:47:43.836081
1824	40	Unidades de conservação	http://bit.ly/1h7CPi7	1820	t	t	2014-10-24 15:47:43.836594
1825	40	A vegetação e os impactos do desmatamento	http://bit.ly/1urxxRP	1820	t	t	2014-10-24 15:47:43.837148
1826	40	A questão ambiental	http://bit.ly/1h7CPi7	1820	t	t	2014-10-24 15:47:43.837651
1827	40	Código Florestal	http://ambiente.educacao.ba.gov.br/conteudos-digitais/conteudo/exibir/id/1637	1820	t	t	2014-10-24 15:47:43.83817
1828	40	A água no planeta e a geomorfologia	\N	1795	t	t	2014-10-24 15:47:43.838678
1829	40	Hidrografia	\N	1828	t	t	2014-10-24 15:47:43.839302
1830	40	Ciclo hidrológico	http://bit.ly/1hz7l3u	1828	t	t	2014-10-24 15:47:43.839818
1831	40	As águas subterrâneas	http://bit.ly/1h3LPzb	1828	t	t	2014-10-24 15:47:43.840336
1832	40	Bacias hidrográficas e redes de drenagem	http://bit.ly/1u85xmj	1828	t	t	2014-10-24 15:47:43.840841
1833	40	As águas oceânicas	http://bit.ly/1h3LPzb	1828	t	t	2014-10-24 15:47:43.841362
1834	40	Geomorfologia	\N	1795	t	t	2014-10-24 15:47:43.841873
1835	40	A formação da Terra	http://bit.ly/1dPjm4T	1834	t	t	2014-10-24 15:47:43.842429
1836	40	Modelado do Relevo Brasileiro	http://bit.ly/1sNz07Y	1834	t	t	2014-10-24 15:47:43.842936
1837	40	Deriva continental e tectônica de placa	http://bit.ly/1dPjm4T	1834	t	t	2014-10-24 15:47:43.843461
1838	40	As províncias geológicas	http://bit.ly/1dPjm4T	1834	t	t	2014-10-24 15:47:43.844015
1839	40	Domínios morfoclimáticos	http://bit.ly/1hZAj9Q	1834	t	t	2014-10-24 15:47:43.844552
1840	40	A fisionomia da paisagem	\N	1795	t	t	2014-10-24 15:47:43.845173
1841	40	A classificação do relevo	http://bit.ly/1sNz07Y	1840	t	t	2014-10-24 15:47:43.845686
1842	40	O relevo submarino	http://bit.ly/1infOWk	1840	t	t	2014-10-24 15:47:43.846211
1843	40	Morfologia litorânea	http://bit.ly/1h7CPi7	1840	t	t	2014-10-24 15:47:43.846716
1844	40	Solo:	\N	1840	t	t	2014-10-24 15:47:43.847286
1845	40	A formação do solo	http://bit.ly/1gV7R73	1844	t	t	2014-10-24 15:47:43.847794
1846	40	Conservação dos solos	http://bit.ly/1ls9DnG	1844	t	t	2014-10-24 15:47:43.848352
1847	40	ENSINO MÉDIO – 2.ª Série	\N	\N	t	t	2014-10-24 15:47:43.848859
1848	40	Dinâmica populacional	\N	1847	t	t	2014-10-24 15:47:43.849371
1849	40	Teorias demográficas	http://bit.ly/ZeeoIj	1848	t	t	2014-10-24 15:47:43.849877
1850	40	Características e crescimento da população mundial	http://bit.ly/ZeeoIj	1848	t	t	2014-10-24 15:47:43.850411
1851	40	Conceitos básicos e crescimento populacional	http://bit.ly/ZeeoIj	1848	t	t	2014-10-24 15:47:43.850916
1852	40	A estrutura da população	http://bit.ly/1fHknar	1848	t	t	2014-10-24 15:47:43.851484
1853	40	Fluxos migratórios	\N	1848	t	t	2014-10-24 15:47:43.852014
1854	40	Imigração	http://bit.ly/1APLWLU	1853	t	t	2014-10-24 15:47:43.852547
1855	40	Emigração	http://bit.ly/1APLWLU	1853	t	t	2014-10-24 15:47:43.853183
1856	40	Êxodo rural	http://bit.ly/1pqr7CO	1853	t	t	2014-10-24 15:47:43.853691
1857	40	População brasileira e suas características	http://bit.ly/1fHknar	1848	t	t	2014-10-24 15:47:43.854213
1858	40	Indicadores sociais e econômicos	\N	1847	t	t	2014-10-24 15:47:43.85472
1859	40	Distribuição de renda	http://bit.ly/1fHknar	1858	t	t	2014-10-24 15:47:43.855304
1860	40	O IDH	http://bit.ly/1pqr7CO	1858	t	t	2014-10-24 15:47:43.855812
1861	40	Espaço, produção e tecnologia	\N	1847	t	t	2014-10-24 15:47:43.856375
1862	40	O mundo globalizado	http://bit.ly/1gIovKY	1861	t	t	2014-10-24 15:47:43.856887
1863	40	A geografia das indústrias	\N	1861	t	t	2014-10-24 15:47:43.857414
1864	40	A revolução industrial	http://bit.ly/Zf7Gly	1863	t	t	2014-10-24 15:47:43.857924
1865	40	Fatores locacionais	http://bit.ly/Zf7Gly	1863	t	t	2014-10-24 15:47:43.858443
1866	40	Divisão internacional do trabalho	http://bit.ly/1h93Gu5	1863	t	t	2014-10-24 15:47:43.858952
1867	40	A industrialização brasileira	\N	1861	t	t	2014-10-24 15:47:43.859502
1868	40	Os caminhos da industrialização brasileira: da sociedade agrária para o urbano- industrial	http://bit.ly/1kUvIvP	1867	t	t	2014-10-24 15:47:43.860152
1869	40	Distribuição espacial da indústria brasileira	http://bit.ly/1iBnQto	1867	t	t	2014-10-24 15:47:43.860663
1870	40	Energia, geopolítica e estratégias	\N	1861	t	t	2014-10-24 15:47:43.861213
1871	40	Fontes de energia	http://bit.ly/1fS255J	1870	t	t	2014-10-24 15:47:43.861721
1872	40	A produção mundial de energia	http://bit.ly/1fS255J	1870	t	t	2014-10-24 15:47:43.862281
1873	40	A matriz energética brasileira	http://bit.ly/1fS255J	1870	t	t	2014-10-24 15:47:43.862788
1874	40	Organização espacial – Brasil e mundo	\N	1847	t	t	2014-10-24 15:47:43.863363
1875	40	Regionalização Mundial	\N	1874	t	t	2014-10-24 15:47:43.863871
1876	40	Processo de desenvolvimento do capitalismo	http://bit.ly/1n5n4I3	1875	t	t	2014-10-24 15:47:43.864415
1877	40	Fases e características	http://bit.ly/1n5n4I3	1875	t	t	2014-10-24 15:47:43.864922
1878	40	O subdesenvolvimento	\N	1874	t	t	2014-10-24 15:47:43.865487
1879	40	Origem e características	http://bit.ly/1n5n4I3	1878	t	t	2014-10-24 15:47:43.866077
1880	40	Mudanças na divisão internacional do trabalho	http://bit.ly/1h93Gu5	1878	t	t	2014-10-24 15:47:43.866588
1881	40	Geopolítica e economia pós II Guerra	\N	1874	t	t	2014-10-24 15:47:43.867108
1882	40	A reordenação política e econômica	http://bit.ly/PYdoUg	1881	t	t	2014-10-24 15:47:43.867617
1883	40	A nova ordem mundial	http://bit.ly/PYdoUg	1881	t	t	2014-10-24 15:47:43.868188
1884	40	O comércio internacional	\N	1874	t	t	2014-10-24 15:47:43.868696
1885	40	Blocos Econômicos	http://bit.ly/1q6ismM	1884	t	t	2014-10-24 15:47:43.869222
1886	40	Brasil	\N	1884	t	t	2014-10-24 15:47:43.869729
1887	40	Regiões brasileiras	http://bit.ly/1fHknar	1884	t	t	2014-10-24 15:47:43.870256
1888	40	Regiões geoeconômicas	http://bit.ly/1fHknar	1884	t	t	2014-10-24 15:47:43.870763
1889	40	Regiões concentradas – Milton Santos	http://bit.ly/1lQkxRb	1884	t	t	2014-10-24 15:47:43.871326
1890	40	Estrutura e organização de espaço	\N	1847	t	t	2014-10-24 15:47:43.871833
1891	40	A urbanização contemporânea	http://bit.ly/1inGKoX	1890	t	t	2014-10-24 15:47:43.872349
1892	40	Rede e hierarquia urbana	\N	1890	t	t	2014-10-24 15:47:43.872862
1893	40	Metrópoles e as cidades globais	http://bit.ly/1lQkxRb	1892	t	t	2014-10-24 15:47:43.873377
1894	40	As cidades e a urbanização no mundo subdesenvolvido	http://bit.ly/1inGKoX	1890	t	t	2014-10-24 15:47:43.873903
1895	40	O processo de urbanização no Brasil	\N	1890	t	t	2014-10-24 15:47:43.874516
1896	40	As metrópoles brasileiras	\N	1895	t	t	2014-10-24 15:47:43.875148
1897	40	Os impactos ambientais urbanos	http://bit.ly/ZBnYp8	1895	t	t	2014-10-24 15:47:43.875653
1898	40	O espaço agrário e a biotecnologia	\N	1890	t	t	2014-10-24 15:47:43.876209
1899	40	O Espaço Agrário	\N	1890	t	t	2014-10-24 15:47:43.87672
1900	40	Tipos de agricultura	http://bit.ly/1gTzFJ0	1899	t	t	2014-10-24 15:47:43.877284
1901	40	A atividade agropecuária no mundo	\N	1899	t	t	2014-10-24 15:47:43.877805
1902	40	Estrutura Fundiária no Brasil	http://bit.ly/1gTzFJ0	1899	t	t	2014-10-24 15:47:43.878355
1903	40	A questão da terra	http://bit.ly/1gTzFJ0	1899	t	t	2014-10-24 15:47:43.878864
1904	40	Agricultura	http://bit.ly/1gTzFJ0	1903	t	t	2014-10-24 15:47:43.879413
1905	40	Agroindústria	http://bit.ly/1eS5BmE	1903	t	t	2014-10-24 15:47:43.879922
1906	40	Biotecnologia	http://bit.ly/1stjtsc	1903	t	t	2014-10-24 15:47:43.880477
1907	40	Transgênicos	http://bit.ly/OFG2cx	1903	t	t	2014-10-24 15:47:43.881089
1908	40	O agronegócio e agricultura orgânica	http://bit.ly/1ef2uVl	1899	t	t	2014-10-24 15:47:43.881595
1909	40	ENSINO MÉDIO – 3.ª Série	\N	\N	t	t	2014-10-24 15:47:43.882169
1910	40	Contexto Histórico e Geopolítico do Mundo Atual	\N	1909	t	t	2014-10-24 15:47:43.882664
1911	40	Processo de desenvolvimento do capitalismo	http://bit.ly/1eVo8J3	1910	t	t	2014-10-24 15:47:43.8833
1912	40	Fases e características	http://bit.ly/1eVo8J3	1911	t	t	2014-10-24 15:47:43.883808
1913	40	O sistema socialista	http://bit.ly/1eVo8J3	1911	t	t	2014-10-24 15:47:43.884373
1914	40	O subdesenvolvimento	http://bit.ly/1inieEu	1910	t	t	2014-10-24 15:47:43.897404
1915	40	Origem e características	\N	1914	t	t	2014-10-24 15:47:43.897918
1916	40	Mudanças na divisão internacional do trabalho	\N	1914	t	t	2014-10-24 15:47:43.898577
1917	40	Geopolítica no pós II Guerra:	http://bit.ly/PktuYs	1910	t	t	2014-10-24 15:47:43.899176
1918	40	 A Guerra Fria	http://bit.ly/1dpakM8	1910	t	t	2014-10-24 15:47:43.899688
1919	40	A reordenação política e econômica	http://bit.ly/1dpakM8	1918	t	t	2014-10-24 15:47:43.900213
1920	40	A nova ordem mundial	http://bit.ly/1dpakM8	1918	t	t	2014-10-24 15:47:43.900719
1921	40	O comércio internacional: os Blocos Econômicos	http://bit.ly/1q6ismM	1910	t	t	2014-10-24 15:47:43.901294
1922	40	Globalização Comércio Mundial, Blocos Econômicos e Conflitos mundiais	\N	1909	t	t	2014-10-24 15:47:43.901804
1923	40	O que é globalização?	http://bit.ly/1t3H6Hr	1922	t	t	2014-10-24 15:47:43.902349
1924	40	Globalização econômica e cultural	http://bit.ly/1t3H6Hr	1922	t	t	2014-10-24 15:47:43.902854
1925	40	Fluxos de informações	http://bit.ly/1gIovKY	1922	t	t	2014-10-24 15:47:43.903419
1926	40	Fluxos de capitais e mercadorias	http://bit.ly/1phkoXI	1922	t	t	2014-10-24 15:47:43.903928
1927	40	Geopolíticas e conflitos no mundo Globalizado	http://bit.ly/1t3H6Hr	1922	t	t	2014-10-24 15:47:43.90445
1928	40	O Estado na economia globalizada:	http://bit.ly/1phkoXI	1922	t	t	2014-10-24 15:47:43.904957
1929	40	o neoliberalismo	http://bit.ly/1gIdE3H	1928	t	t	2014-10-24 15:47:43.905503
1930	40	As multinacionais	http://bit.ly/1gIdE3H	1928	t	t	2014-10-24 15:47:43.906148
1931	40	O comércio global	http://bit.ly/1phkoXI	1928	t	t	2014-10-24 15:47:43.906659
1932	40	Organismos internacionais	http://bit.ly/1nyBtfS	1922	t	t	2014-10-24 15:47:43.907212
1933	40	A OMC	http://bit.ly/1q4VJuM	1932	t	t	2014-10-24 15:47:43.907724
1934	40	O Brasil e a economia global: a balança comercial	http://bit.ly/1phkoXI	1922	t	t	2014-10-24 15:47:43.908278
1935	40	Por uma outra globalização: Fórum Social Mundial	http://bit.ly/1t3H6Hr	1922	t	t	2014-10-24 15:47:43.908784
1936	40	Espaço produção e tecnologia	\N	1909	t	t	2014-10-24 15:47:43.909392
1937	40	A questão energética no Mundo	http://bit.ly/ZBnYp8	1936	t	t	2014-10-24 15:47:43.9099
1938	40	Fontes de energia alternativa no Brasil:	http://bit.ly/1d8f0FK	1936	t	t	2014-10-24 15:47:43.910421
1939	40	solar	\N	1938	t	t	2014-10-24 15:47:43.910925
1940	40	álcool	\N	1938	t	t	2014-10-24 15:47:43.911484
1941	40	energia eólica	\N	1938	t	t	2014-10-24 15:47:43.912079
1942	40	biogás	http://bit.ly/PxS9sA	1938	t	t	2014-10-24 15:47:43.91259
1943	40	biomassa	http://bit.ly/PxS9sA	1938	t	t	2014-10-24 15:47:43.913118
1944	40	O petróleo no Brasil	http://bit.ly/1j0pZ03	1936	t	t	2014-10-24 15:47:43.913625
1945	40	A indústria no Mundo Atual: fordismo, taylorismo e toyotismo	http://bit.ly/1t3H6Hr	1936	t	t	2014-10-24 15:47:43.91419
1946	40	Países industrializados e industrialização periférica:	http://bit.ly/1dmfckN	1936	t	t	2014-10-24 15:47:43.914695
1947	40	América Latina	http://bit.ly/1pgpqUD	1946	t	t	2014-10-24 15:47:43.915222
1948	40	Tigres Asiáticos	\N	1946	t	t	2014-10-24 15:47:43.91573
1949	40	novos Tigres, China e a ZEE’S e Índia	http://bit.ly/PYdoUg	1946	t	t	2014-10-24 15:47:43.916288
1950	40	Espaço geográfico e sustentabilidade	\N	1909	t	t	2014-10-24 15:47:43.916796
1951	40	Problemas ambientais a origem	http://bit.ly/1dmfckN	1950	t	t	2014-10-24 15:47:43.917313
1952	40	Globalização e sustentabilidade	http://bit.ly/1t3H6Hr	1950	t	t	2014-10-24 15:47:43.91782
1953	40	Questão ambiental e os interesses econômicos	http://bit.ly/1dmfckN	1950	t	t	2014-10-24 15:47:43.918361
1954	40	Diretrizes para o desenvolvimento sustentável: Rio 92, Rio + 10, Protocolo de Kyoto, Agenda 21	http://bit.ly/ZBnYp8	1950	t	t	2014-10-24 15:47:43.918881
1955	40	Recursos naturais e geopolíticos	http://bit.ly/1j3CYym	1950	t	t	2014-10-24 15:47:43.919399
1956	40	Consciência Ecológica – movimentos e conferências	http://bit.ly/ZBnYp8	1950	t	t	2014-10-24 15:47:43.919904
1957	40	Fenômenos climáticos globais	http://bit.ly/ZBnYp8	1950	t	t	2014-10-24 15:47:43.920418
1958	40	Políticas ambientais no Brasil: o código florestal e das águas	http://bit.ly/1j3CYym	1950	t	t	2014-10-24 15:47:43.92093
1054	41	ENSINO MÉDIO – 1.ª Série	\N	\N	t	t	2014-10-24 15:47:43.921513
1055	41	Os primeiros grupos humanos	\N	1054	t	t	2014-10-24 15:47:43.922082
1056	41	Evolução do pensamento historiográfico	\N	1054	t	t	2014-10-24 15:47:43.922591
1057	41	Civilizações do oriente próximo e surgimento do estado como organismo político:	http://bit.ly/1s90Xp3	1054	t	t	2014-10-24 15:47:43.923161
1058	41	Civilização egípcia	http://bit.ly/1s9g9Cy	1057	t	t	2014-10-24 15:47:43.923672
1059	41	Mesopotâmia	http://bit.ly/1dq4jic	1057	t	t	2014-10-24 15:47:43.92421
1060	41	Hebraica	http://bit.ly/PkCUTS	1057	t	t	2014-10-24 15:47:43.924724
1061	41	Fenícia	http://bit.ly/1fKYp5A	1057	t	t	2014-10-24 15:47:43.925287
1062	41	Persa	http://bit.ly/1gcaRBc	1057	t	t	2014-10-24 15:47:43.925793
1063	41	China	http://bit.ly/1gcaRBc	1057	t	t	2014-10-24 15:47:43.926361
1064	41	África	http://bit.ly/1s9NkWK	1057	t	t	2014-10-24 15:47:43.92687
1065	41	Constituição da cidadania clássica e as relações sociais marcadas pela expansão territorial escravista:	\N	1054	t	t	2014-10-24 15:47:43.92741
1066	41	Grécia	http://bit.ly/1vXuOka	1065	t	t	2014-10-24 15:47:43.92792
1067	41	Roma	http://bit.ly/1vXvia1	1065	t	t	2014-10-24 15:47:43.928447
1068	41	O império alexandrino e a fusão oriente/ocidente	http://bit.ly/1gcaRBc	1065	t	t	2014-10-24 15:47:43.928957
1069	41	A prática escravista na África Antiga com outros grandes reinos africanos	\N	1054	t	t	2014-10-24 15:47:43.929511
1070	41	Kongo	\N	1069	t	t	2014-10-24 15:47:43.930158
1071	41	Mali	\N	1069	t	t	2014-10-24 15:47:43.93067
1072	41	A presença da África como um universo histórico-cultural diverso e complexo antes da escravidão atlântica	\N	1069	t	t	2014-10-24 15:47:43.931209
1073	41	Egito antigo e cinco milênios de civilização	http://bit.ly/1vXRGQE	1069	t	t	2014-10-24 15:47:43.931713
1074	41	O mundo medieval: ruralização, servidão, vassalagem e expansão do poder religioso	http://bit.ly/1EPAX72	1069	t	t	2014-10-24 15:47:43.932285
1075	41	A expansão islâmica e sua presença na península Ibérica	http://bit.ly/1d4xreo	1069	t	t	2014-10-24 15:47:43.93291
1076	41	A vida na América antes da conquista européia:	http://bit.ly/1eWkwq4	1054	t	t	2014-10-24 15:47:43.933483
1077	41	Maias	http://bit.ly/1gIdObn	1076	t	t	2014-10-24 15:47:43.934102
1078	41	Incas	http://bit.ly/1gIdObn	1076	t	t	2014-10-24 15:47:43.934611
1079	41	Astecas	http://bit.ly/1gIdObn	1076	t	t	2014-10-24 15:47:43.935193
1080	41	O lugar da América no imaginário europeu	\N	1076	t	t	2014-10-24 15:47:43.935697
1081	41	Sociedades indígenas no território brasileiro antes da colonização portuguesa	http://bit.ly/1EPFPJt	1076	t	t	2014-10-24 15:47:43.936215
1082	41	ENSINO MÉDIO – 2.ª Série	\N	\N	t	t	2014-10-24 15:47:43.936773
1083	41	O Renascimento e os novos paradigmas no ordenamento do mundo	http://bit.ly/1vXnR2G	1082	t	t	2014-10-24 15:47:43.937283
1084	41	Arte e ciência na ordem social	\N	1083	t	t	2014-10-24 15:47:43.937792
1085	41	Conseqüências para a nova ordem econômica do mundo e do surgimento da modernidade	http://bit.ly/1gVWZW4	1083	t	t	2014-10-24 15:47:43.93836
1087	41	A arte e a ciência: profissão e desenvolvimento cultural no Renascimento	\N	1083	t	t	2014-10-24 15:47:43.938887
1088	41	Crítica ao sistema clerical e a reforma e contra-reforma no contexto de disputa por uma nova ordem	http://bit.ly/1gI1dqk	1083	t	t	2014-10-24 15:47:43.939414
1089	41	A importância política dos reis:	\N	1082	t	t	2014-10-24 15:47:43.939928
1090	41	Formação das monarquias nacionais	\N	1089	t	t	2014-10-24 15:47:43.940456
1091	41	Portugal	\N	1089	t	t	2014-10-24 15:47:43.941013
1092	41	Espanha	http://bit.ly/1gI1dqk	1089	t	t	2014-10-24 15:47:43.941547
1093	41	Inglaterra	http://bit.ly/1EYQ5iG	1089	t	t	2014-10-24 15:47:43.942165
1094	41	França	http://bit.ly/1d3nYEa	1089	t	t	2014-10-24 15:47:43.942683
1095	41	Mercantilismo e fortalecimento do vínculo entre os estados nacionais e a burguesia	http://bit.ly/1eVK32T	1089	t	t	2014-10-24 15:47:43.943218
1096	41	A expansão ultramarina no contexto da lógica econômica mercantilista	http://bit.ly/1nPK01Y	1089	t	t	2014-10-24 15:47:43.943726
1097	41	A presença portuguesa nas grandes navegações	http://bit.ly/1n4F5qh	1089	t	t	2014-10-24 15:47:43.944284
1098	41	Encontro europeu com o continente africano e suas diversidades	http://bit.ly/1lQbHmu	1089	t	t	2014-10-24 15:47:43.944795
1099	41	A transformação da escravidão pelos europeus num sistema comercial rentável para os colonizadores	http://bit.ly/R0KR1n	1089	t	t	2014-10-24 15:47:43.945358
1100	41	Sociedades africanas no século XV	http://bit.ly/1d4oNwu	1089	t	t	2014-10-24 15:47:43.945868
1101	41	A Europa e o Novo Mundo:	\N	1082	t	t	2014-10-24 15:47:43.946393
1102	41	Relações econômicas, sociais e culturais do sistema colonial	http://bit.ly/1u5RKKP	1101	t	t	2014-10-24 15:47:43.946897
1103	41	O Brasil colonial no contexto da lógica mercantilista e da expansão marítima	http://bit.ly/1EYZSp0	1101	t	t	2014-10-24 15:47:43.947413
1104	41	Tráfico negreiro e escravismo africano no Brasil	http://bit.ly/1hPMG7g	1101	t	t	2014-10-24 15:47:43.94793
1105	41	A escravidão	http://bit.ly/1pfzc8A	1101	t	t	2014-10-24 15:47:43.948475
1106	41	A rota dos escravos	http://bit.ly/1rbFhEa	1101	t	t	2014-10-24 15:47:43.949022
1107	41	O Iluminismo	http://bit.ly/1u67pts	1101	t	t	2014-10-24 15:47:43.949558
1108	41	Absolutismo	http://bit.ly/1EZ7SWW	1101	t	t	2014-10-24 15:47:43.950182
1109	41	Reação ao absolutismo europeu	\N	1101	t	t	2014-10-24 15:47:43.950689
1110	41	Emergência do liberalismo como doutrina política	http://bit.ly/1EZf5Xc	1101	t	t	2014-10-24 15:47:43.951214
1111	41	O movimento iluminista	http://bit.ly/1u67pts	1101	t	t	2014-10-24 15:47:43.951722
1112	41	A convergência entre o liberalismo e o iluminismo na consolidação dos estados nacionais	\N	1101	t	t	2014-10-24 15:47:43.952291
1113	41	Revoluções inglesa (século XVII)	http://bit.ly/1u6h3wn	1101	t	t	2014-10-24 15:47:43.952799
1114	41	Independência dos Estados Unidos (século XVIII)	http://bit.ly/1eWqNSQ	1101	t	t	2014-10-24 15:47:43.953345
1115	41	A Revolução Francesa:	http://bit.ly/1EZ4OKx	1082	t	t	2014-10-24 15:47:43.953856
1116	41	Processo	http://bit.ly/1EZ4OKx	1115	t	t	2014-10-24 15:47:43.954411
1117	41	Dinâmica e fase final	http://bit.ly/1EZ4OKx	1115	t	t	2014-10-24 15:47:43.954923
1118	41	O poder napoleônico	http://bit.ly/1EZ4OKx	1115	t	t	2014-10-24 15:47:43.955441
1119	41	Controle social e político	http://bit.ly/1EZ4OKx	1115	t	t	2014-10-24 15:47:43.955951
1120	41	A Revolução Industrial:	http://bit.ly/1d4JhFu	1082	t	t	2014-10-24 15:47:43.956491
1121	41	Nova forma de organização econômica e o pioneirismo inglês	\N	1120	t	t	2014-10-24 15:47:43.957148
1122	41	A força da transformação da estrutura agrária	http://bit.ly/1iBnQto	1120	t	t	2014-10-24 15:47:43.95766
1123	41	A expansão da Revolução Industrial	http://bit.ly/1ooM8xV	1120	t	t	2014-10-24 15:47:43.958213
1124	41	A produção industrial	http://bit.ly/1u6ki6O	1120	t	t	2014-10-24 15:47:43.958722
1125	41	As novas doutrinas sociais	http://bit.ly/1u63A7x	1120	t	t	2014-10-24 15:47:43.959367
1126	41	Fim da escravidão no Brasil	http://bit.ly/Pjleb7	1120	t	t	2014-10-24 15:47:43.959874
1127	41	O imperialismo das grandes potências mundiais no século XIX	http://bit.ly/1dIiVnK	1120	t	t	2014-10-24 15:47:43.960416
1128	41	I Grande Guerra Mundial	http://bit.ly/1eWuqrP	1120	t	t	2014-10-24 15:47:43.960946
1129	41	A Revolução Russa de 1917	http://bit.ly/1pfQwdF	1120	t	t	2014-10-24 15:47:43.961478
1130	41	ENSINO MÉDIO – 3.ª Série	\N	\N	t	t	2014-10-24 15:47:43.962032
1131	41	Brasil Contemporâneo	\N	1130	t	t	2014-10-24 15:47:43.962534
1132	41	O Brasil República do último império sul-americano ao fortalecimento do exército	\N	1131	t	t	2014-10-24 15:47:43.963149
1133	41	A República Oligárquica	\N	1132	t	t	2014-10-24 15:47:43.96366
1134	41	A exclusão popular do centro do poder republicano	http://bit.ly/1sNdCOz	1132	t	t	2014-10-24 15:47:43.96422
1135	41	As reações anti-republicanas	\N	1132	t	t	2014-10-24 15:47:43.964728
1136	41	A produção artística e literária no âmbito das reações à república oligárquica	\N	1132	t	t	2014-10-24 15:47:43.96528
1137	41	Crise da República Velha	http://bit.ly/1pfUEuk	1132	t	t	2014-10-24 15:47:43.965784
1138	41	Canudos	http://bit.ly/1sNfxm1	1132	t	t	2014-10-24 15:47:43.966308
1139	41	Guerra do contestado	http://bit.ly/1phCNUr	1132	t	t	2014-10-24 15:47:43.966817
1140	41	O tenentismo e a contestação aberta à oligarquia	\N	1132	t	t	2014-10-24 15:47:43.967359
1141	41	A crise de 1929	http://bit.ly/1phjfQ0	1130	t	t	2014-10-24 15:47:43.967868
1142	41	Do modelo agrário para o industrial	http://bit.ly/1ooM8xV	1130	t	t	2014-10-24 15:47:43.968418
1143	41	A mão de obra escrava no pós-abolição – política de exclusão por dentro do estado	http://bit.ly/1sMYvo0	1130	t	t	2014-10-24 15:47:43.968927
1144	41	Os regimes Totalitários	http://bit.ly/1jSc1lo	1130	t	t	2014-10-24 15:47:43.969559
1145	41	A II Grande Guerra Mundial	http://bit.ly/1eWvmMU	1130	t	t	2014-10-24 15:47:43.97015
1146	41	A Guerra Fria e as transformações do mundo na segunda metade do século XX	http://bit.ly/1sMQ38y	1130	t	t	2014-10-24 15:47:43.970658
1147	41	O Brasil de Vargas e suas transformações	http://bit.ly/VuXLq3	1130	t	t	2014-10-24 15:47:43.97121
1148	41	A República popular democrática	http://bit.ly/1h27bNt	1130	t	t	2014-10-24 15:47:43.971723
1149	41	O Regime militar e os conflitos políticos no Brasil	http://bit.ly/1gUVKqg	1130	t	t	2014-10-24 15:47:43.972249
1150	41	Regime militar	http://bit.ly/1gUVKqg	1149	t	t	2014-10-24 15:47:43.972758
1151	41	Ditadura militar	http://bit.ly/1gUVKqg	1149	t	t	2014-10-24 15:47:43.973283
1152	41	Crise econômica mundial	http://bit.ly/1sNjDKT	1130	t	t	2014-10-24 15:47:43.973798
1153	41	Conflitos internacionais atuais	http://bit.ly/1sNbR3G	1130	t	t	2014-10-24 15:47:43.974355
1154	41	Cultura afro-brasileira lei 10.639	http://bit.ly/PUUheq	1130	t	t	2014-10-24 15:47:43.974861
1155	41	história Indígena lei 11.645	http://bit.ly/1phE2Tu	1130	t	t	2014-10-24 15:47:43.975458
1258	41	Industrialização brasileira	http://bit.ly/1sNkbQZ	1130	t	t	2014-10-24 15:47:43.976011
\.


--
-- TOC entry 3125 (class 0 OID 0)
-- Dependencies: 197
-- Name: componentecurriculartopico_idcomponentecurriculartopico_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('componentecurriculartopico_idcomponentecurriculartopico_seq', 1, false);


--
-- TOC entry 3126 (class 0 OID 0)
-- Dependencies: 198
-- Name: comuagenda_idcomunidaderelacionada_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('comuagenda_idcomunidaderelacionada_seq', 15, true);


--
-- TOC entry 2821 (class 0 OID 114642)
-- Dependencies: 200 2922
-- Data for Name: comunidade; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY comunidade (idcomunidade, idfavorito, idusuario, nomecomunidade, descricao, datacriacao, qtdvisitas, flpendente, avaliacao, flmoderausuario, ativa) FROM stdin;
\.


--
-- TOC entry 3127 (class 0 OID 0)
-- Dependencies: 199
-- Name: comunidade_idcomunidade_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('comunidade_idcomunidade_seq', 113, true);


--
-- TOC entry 2822 (class 0 OID 114654)
-- Dependencies: 201 2922
-- Data for Name: comunidadeagenda; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY comunidadeagenda (idcomunidadeagenda, idcomunidade, datainicio, datafim, evento, mensagem, link1, linktitulo1, link2, linktitulo2, link3, linktitulo3, local) FROM stdin;
\.


--
-- TOC entry 3128 (class 0 OID 0)
-- Dependencies: 202
-- Name: comunidadeagenda_idcomunidadeagenda_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('comunidadeagenda_idcomunidadeagenda_seq', 1, false);


--
-- TOC entry 2824 (class 0 OID 114663)
-- Dependencies: 203 2922
-- Data for Name: comunidadealbum; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY comunidadealbum (idcomunidade, idcomunidadealbum, datacriacao, titulo) FROM stdin;
\.


--
-- TOC entry 3129 (class 0 OID 0)
-- Dependencies: 204
-- Name: comunidadealbum_idcomunidadealbum_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('comunidadealbum_idcomunidadealbum_seq', 30, true);


--
-- TOC entry 2826 (class 0 OID 114669)
-- Dependencies: 205 2922
-- Data for Name: comunidadealbumfoto; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY comunidadealbumfoto (idcomunidadealbumfoto, idcomunidadealbum, legenda, extensao, flperfil, datacriacao) FROM stdin;
\.


--
-- TOC entry 3130 (class 0 OID 0)
-- Dependencies: 206
-- Name: comunidadealbumfoto_idcomunidadealbumfoto_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('comunidadealbumfoto_idcomunidadealbumfoto_seq', 64, true);


--
-- TOC entry 2828 (class 0 OID 114675)
-- Dependencies: 207 2922
-- Data for Name: comunidadeblog; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY comunidadeblog (idcomunidadeblog, titulo, datacriacao, texto, idcomunidade, idusuario) FROM stdin;
\.


--
-- TOC entry 3131 (class 0 OID 0)
-- Dependencies: 208
-- Name: comunidadeblog_idcomunidadeblog_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('comunidadeblog_idcomunidadeblog_seq', 69, true);


--
-- TOC entry 2830 (class 0 OID 114684)
-- Dependencies: 209 2922
-- Data for Name: comunidadefoto; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY comunidadefoto (idcomunidadefoto, idcomunidade, extensao) FROM stdin;
\.


--
-- TOC entry 3132 (class 0 OID 0)
-- Dependencies: 210
-- Name: comunidadefoto_idcomunidadefoto_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('comunidadefoto_idcomunidadefoto_seq', 56, true);


--
-- TOC entry 2832 (class 0 OID 114692)
-- Dependencies: 211 2922
-- Data for Name: comunidadesugerida; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY comunidadesugerida (idusuario, idcomunidade, idusuarioconvite, visto, dataconvite) FROM stdin;
\.


--
-- TOC entry 2833 (class 0 OID 114696)
-- Dependencies: 212 2922
-- Data for Name: comunidadetag; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY comunidadetag (idcomunidade, idtag) FROM stdin;
\.


--
-- TOC entry 2835 (class 0 OID 114701)
-- Dependencies: 214 2922
-- Data for Name: comurelacionada; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY comurelacionada (idcomurelacionada, idcomunidaderelacionada, idcomunidade) FROM stdin;
\.


--
-- TOC entry 3133 (class 0 OID 0)
-- Dependencies: 213
-- Name: comurelacionada_idcomurelacionada_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('comurelacionada_idcomurelacionada_seq', 142, true);


--
-- TOC entry 2837 (class 0 OID 114707)
-- Dependencies: 216 2922
-- Data for Name: comutopico; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY comutopico (idcomutopico, idcomunidade, idusuario, titulo, datacriacao, mensagem) FROM stdin;
\.


--
-- TOC entry 3134 (class 0 OID 0)
-- Dependencies: 215
-- Name: comutopico_idcomutopico_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('comutopico_idcomutopico_seq', 147, true);


--
-- TOC entry 2839 (class 0 OID 114717)
-- Dependencies: 218 2922
-- Data for Name: comutopicomsg; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY comutopicomsg (idcomutopicomsg, idusuario, mensagem, datacriacao, idcomutopico, pai, ativo) FROM stdin;
\.


--
-- TOC entry 3135 (class 0 OID 0)
-- Dependencies: 217
-- Name: comutopicomsg_idcomutopicomsg_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('comutopicomsg_idcomutopicomsg_seq', 733, true);


--
-- TOC entry 2840 (class 0 OID 114727)
-- Dependencies: 219 2922
-- Data for Name: comuusuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY comuusuario (idcomunidade, idusuario, datacriacao, flmoderador, idcomuusuario, bloqueado, flpendente) FROM stdin;
\.


--
-- TOC entry 3136 (class 0 OID 0)
-- Dependencies: 220
-- Name: comuusuario_idcomuusuario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('comuusuario_idcomuusuario_seq', 1591, true);


--
-- TOC entry 2843 (class 0 OID 114736)
-- Dependencies: 222 2922
-- Data for Name: comuvoto; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY comuvoto (idcomuvoto, idcomunidade, idusuario, voto, datacriacao) FROM stdin;
\.


--
-- TOC entry 3137 (class 0 OID 0)
-- Dependencies: 221
-- Name: comuvoto_idcomuvoto_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('comuvoto_idcomuvoto_seq', 63, true);


--
-- TOC entry 2845 (class 0 OID 114743)
-- Dependencies: 224 2922
-- Data for Name: conteudodigital; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY conteudodigital (idconteudodigital, idusuariopublicador, idusuarioaprova, idformato, titulo, autores, fonte, descricao, acessibilidade, tamanho, datapublicacao, datacriacao, flaprovado, qtddownloads, avaliacao, acessos, idformatoguiapedagogico, licenca, site, idformatodownload, idlicencaconteudo, idcanal, flsitetematico, idservidor, fldestaque, idconteudodigitalcategoria) FROM stdin;
\.


--
-- TOC entry 3138 (class 0 OID 0)
-- Dependencies: 223
-- Name: conteudodigital_idconteudodigital_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('conteudodigital_idconteudodigital_seq', 4631, true);


--
-- TOC entry 2846 (class 0 OID 114760)
-- Dependencies: 225 2922
-- Data for Name: conteudodigitalcategoria; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY conteudodigitalcategoria (idconteudodigitalcategoria, nomeconteudodigitalcategoria, descricaoconteudodigitalcategoria, idconteudodigitalcategoriapai, flativo, datacriacao, idcanal, fldestaque) FROM stdin;
1	Almanaque Viramundo	A Série colabora com a ampliação da noção de espaço educativo, conhecimento e saberes e a percepção da realidade dos educadores, além de inter-relacionar formas de conhecimento e conteúdos de diferentes disciplinas. Oferece, ainda, à comunidade escolar um programa informativo e dinâmico, priorizando reflexões e discussões sobre movimentos artísticos, obras de arte, desenvolvimento das ciências no mundo, apresentação das diversas categorias geográficas, apresentando uma Bahia diversa, única, longe do que é mostrada no mercado midiático.	\N	t	2014-10-24 15:45:53.912917	1	f
2	Campanhas Educativas	As campanhas educativas despertam reflexões e discussões numa lógica contextualizada e interdisciplinar que contempla os temas transversais: Valorização e formação do educador, Saúde e Sexualidade, Saúde do professor, Meio Ambiente, Cidadania e Direitos Humanos.	\N	t	2014-10-24 15:45:53.913924	1	f
3	Dois Dedos de Prosa	\nO Dois Dedos de Prosa tem como objetivos divulgar as pesquisas realizadas pelas universidades; aproximar o ensino superior da educação básica e problematizar temáticas pertinentes ao universo da educação pública, também pretende contribuir na formação de professores, estimulando os profissionais da educação à reflexão e discussão da educação em seus diferentes âmbitos.\n\nO programa promove um diálogo entre dois especialistas em educação que pesquisem um mesmo tema e que tenham pontos de vista diversos ou complementares, numa ambiência que remeta à ideia de uma prosa entre educadores. Cada programa destina-se a um território identitário, cada um com uma temática de especial interesse.\n\n	\N	t	2014-10-24 15:45:53.91434	1	f
16	Historias da Bahia	Série aborda fatos históricos da Bahia com recursos teatrais, computação gráfica e opinião de pesquisadores	9	t	2014-10-24 15:45:53.918909	1	f
10	Cotidiano	Série de ficção protagonizada por estudantes da escola pública que relaciona experiêcias cotidianas e o conhecimento científico	9	t	2014-10-24 15:45:53.916595	1	f
4	EmCenaAção	O programa EnCenAção da série Teleteatro, da TV Anísio Teixeira, tem por objetivo divulgar, valorizar e disseminar a arte cênica, bem como os artistas locais, através de peças adaptadas para o audiovisual, estimulando nos estudantes da Rede Pública do Estado da Bahia a formação de plateia, a produção de textos teatrais e a arte de interpretar, além de construir produtos audiovisuais com conteúdos de interesse pedagógico, estimulando no professor o uso da teledramaturgia em sala de aula, fomentando assim discussões em torno de temas pouco abordados no dia-a-dia.	\N	t	2014-10-24 15:45:53.914655	1	f
5	Etnomatemática	Nos episódios da série, a matemática é apresentada de forma espontânea, valorizando ações cotidianas dos vários grupos sociais as quais se relacionam com a matemática formal. Nesse sentido, tal interprograma desenvolve-se numa lógica contextualizada e interdisciplinar. Com duração de 60”, os episódios foram gravados nas dependências internas e externas de escolas.	\N	t	2014-10-24 15:45:53.914951	1	f
6	Faça Acontecer	\n\nO Faça Acontecer apresenta o estudante Lucas Borges, do Colégio Estadual Nossa Senhora de Fátima, no município de Fátima, a 319 Km de Salvador. Lucas, que sempre demonstrou habilidades para a engenharia e para a invenção, criou o projeto premiado na Feira de Ciências da Bahia, em 2011, e na FEBRACE - Feira Brasileira de Ciências e Engenharia, 2012. A invenção de Lucas diz respeito à criação de um sistema de segurança para fogões contra acidentes domésticos. A professora Cláudia de Souza Santana foi orientadora do projeto.\n\nSérie de documentários com estudantes de escolas públicas baianas que se destacaram em festivais, campeonatos e/ou atividades apoiadas pela SEC. Tem como objetivo valorizar a capacidade intelectual, artística e esportiva dos estudantes da Rede Pública de Ensino da Bahia, a partir de depoimentos deles mesmos, dos seus familiares, professores e amigos. Desta forma, o FAÇA ACONTECER apresenta como personagem principal um estudante da escola pública da Bahia, enfatizando a territorialidade como elemento marcante (oralidade, comportamentos, expressões culturais), a escola pública como mediadora entre o estudante e o mundo, os professores também como protagonistas desse processo, ratificando os projetos promovidos pela SEC (TAL, FACE, AVE, Feira de Ciências) como importantes eventos nos quais os estudantes podem demonstrar seus potenciais.\n\nEsses documentários possuem um caráter híbrido, pois envolvem, em sua narrativa, diversos conteúdos como: expressões artísticas, diversidades linguísticas, categorias geográficas, gêneros literários, entre outro, assim podem ser trabalhados, em sala de aula, nas disciplinas: Artes, Geografia, Língua Portuguesa, Literatura, História e Sociologia, garantindo a interdisciplinaridade.\n\n	\N	t	2014-10-24 15:45:53.915326	1	f
21	Muito Prazer	A série Muito Prazer tem como objetivo de abordar a sexualidade nas dimensões biológica e sociocultural, a partir de orientações inseridas nos eixos Diversidade e Direitos Humanos, possibilitando à Comunidade Escolar uma percepção mais objetiva da relevância da sexualidade na construção da(s) identidade(s) dos indivíduos. Os temas trabalhados incluem, também, o debate sobre o combate à violência entre os gêneros, à homofobia, além de informações quanto ao sexo seguro e ao planejamento familiar.	\N	t	2014-10-24 15:45:53.920582	1	f
7	Ginga, Corpo e Cultura	O programa aborda conteúdos de disciplinas como Educação Física, Biologia, História, Artes dentre outras, além de registrar e incentivar as práticas da cultura corporal e desportiva da rede pública de Educação. Um dos objetivos da série é dar visibilidade a diversas expressões desta cultura e de esportes cuja prática ainda é pouco conhecida do ambiente escolar, a exemplo do xadrez, dos esportes olímpicos e radicais; valorizar expressões artísticas como danças, artes circenses, brincadeiras; incentivar a prática de esportes pelo gênero feminino. Traz informações e dicas de saúde e qualidade de vida, abordando a cultura corporal e esportiva como uma prática que colabora para a saúde, a aprendizagem da vida em sociedade, o respeito às regras e limites de forma crítica e problematizada.	\N	t	2014-10-24 15:45:53.915659	1	f
8	Identidades	\nO programa é um amplo festival de arte e cultura em que os convidados e plateia interagem, demonstrando o quanto a sua produção artística é ao mesmo tempo atual e ligada às raízes da tradição, incluindo música, dança, teatro, circo e artes visuais, bem como expressões do rock baiano, do reggae, do samba-de-roda, do pagode, do forró, do axé, do hip-hop, dentre outros, fazem do programa Identidades um produto único, reunindo diferentes modalidades artísticas em torno de um tema universal, por exemplo: Saudade, Paixão, Ciúmes, Sonho, Liberdade, Medo e outros. É bastante atual, vibrante e contempla a nossa diversidade cultural, tornando-se assim, um registro, um pequeno mapeamento cultural da Bahia, com grande potencial interdisciplinar.\n\nO objetivo principal do programa Identidades é registrar e disseminar através de conteúdo audiovisual a diversidade de manifestações culturais e expressões artísticas do Estado da Bahia, assim, ele pode ser utilizado em sala de aula em diferentes áreas do conhecimento. O Identidades é um produto audiovisual gravado ao vivo em teatro com a presença de estudantes de escolas públicas na plateia, tendo um apresentador, um DJ e atrações musicais intercaladas com números circenses, de dança, teatro e artes visuais.\n\n	\N	t	2014-10-24 15:45:53.915974	1	f
11	Diversidades	A opinião da comunidade escolar sobre temas universais e comportamento social em formato dinâmico e multimídia	9	t	2014-10-24 15:45:53.917298	1	f
12	EmCenaAção	Adaptações de peças teatrais para a TV protagonizadas por estudantes, professores e atores regionais	9	t	2014-10-24 15:45:53.917631	1	f
13	Faça Acontecer	Documentários que retratam a vida de estudantes que se destacaram nos projetos artísticos, científicos e esportivos da Secretaria da Educação da Bahia	9	t	2014-10-24 15:45:53.91795	1	f
14	Filmei!	Produção audiovisual autônoma de estudantes e professores da escola pública	9	t	2014-10-24 15:45:53.91828	1	f
15	Gramofone	Série que mostra os bastidores e apresentações de trabalhos musicais de estudantes e professores da escola pública	9	t	2014-10-24 15:45:53.918594	1	f
17	Minha Escola, Meu Lugar	Documentários que mostram a relação entre a escola e a comunidade, a partir do olhar de estudante	9	t	2014-10-24 15:45:53.919288	1	f
18	Ser Professor	Documentários sobre práticas pedagógicas e iniciativas criativas de professores da Rede Estadual de Ensino	9	t	2014-10-24 15:45:53.919605	1	f
19	Máquina de Democracia	O Máquina de Democracia é um programa de jornalismo especializado em Educação para a Rede Pública de Ensino da Bahia que se relaciona com as bases legais que norteiam a Educação no país, a exemplo das Leis 9394/96, 10.639/2003 e 11.645/2008 e dos Parâmetros Curriculares Nacionais. A série tem a missão de fomentar a discussão e a reflexão, ampliando o conteúdo curricular, além de socializar experiências educacionais exitosas. As pautas do programa são norteadas por necessidades da rede de educadores, revendo conceitos, revisitando os Temas Transversais, sugerindo e incentivando a interdisciplinaridade. As edições também têm um viés para a formação continuada dos professores da Rede.	\N	t	2014-10-24 15:45:53.919924	1	f
20	Meu avô, o circo	Meu avô, circo leva a essência brincante do circo para a comunidade escolar, vislumbrando o “circo como o avô da televisão” (Meu avô, o circo). Por sua dimensão lúdica, os episódios podem ser utilizados por várias áreas do currículo escolar, principalmente como disparadores de reflexões e discussões.	\N	t	2014-10-24 15:45:53.920283	1	f
22	Poesia de Cada Dia	A principal finalidade é ampliar contato com este tipo de texto e formar nos alunos e professores o gosto pela poesia, a capacidade de lê-la, apreciá-la e, consequentemente, produzi-la. Os textos selecionados são de diversos autores, estilos e tamanhos, em formato digital, entremeados por imagens que os ilustram. São declamados por voz adulta, conforme o conteúdo da poesia, com 30 a 60 segundos de duração.	\N	t	2014-10-24 15:45:53.920874	1	f
23	Questão de Língua	A série apresenta informações acerca da gramática da língua portuguesa, do espanhol e do inglês para a comunidade escolar, estabelecendo uma rede de comunicação e informação numa perspectiva inter e transdisciplinar. Toma como concepção a ideia de que a língua é o resultado da interação de sujeitos sociais; e, que, por meio de ações linguísticas e sociolinguísticas se manifestam. O objetivo principal do programa é promover discussões sobre o uso da Língua Portuguesa, considerando o contexto do falante.	\N	t	2014-10-24 15:45:53.921299	1	f
24	Curso de Interpretação para TV e Produção Audiovisual	O curso tem como objetivo principal formar estudantes e professores da rede pública estadual para a atuação diante das câmeras e produção de vídeos. Durante o processo, os cursistas têm acesso aos conteúdo teóricos e práticos de uma produção audiovisual desde o roteiro à edição, tendo como fio condutor a arte de interpretar. Ao final, um roteiro é escolhido para gravação, consolidando o aprendizado na a prática.	\N	t	2015-09-24 17:21:53.247191	1	f
26	Vitória da Conquista	Vitória da Conquista	24	t	2015-10-27 08:02:00.807902	1	f
28	Formosa do Rio Preto	Formosa do Rio Preto	24	t	2015-10-28 09:55:13.52625	1	f
29	Santa Maria da Vitória	Santa Maria da Vitória	24	t	2015-10-28 09:56:17.611678	1	f
30	Juazeiro	Juazeiro	24	t	2015-10-28 09:57:02.035109	1	f
9	Intervalo	<p>O Programa Intervalo, uma produção inspirada no intervalo escolar, realizada e protagonizada por professores e estudantes das escolas públicas baianas.</p>\r\n<p>O Programa evidencia a riqueza artística, cultural e científica da comunidade escolar baiana, dando destaque às experiências exitosas de <b>escolas</b>, <b>estudantes</b> e <b>professores</b> da rede pública <b>que fazem a diferença na educação</b>. Dentre os quadros do programa, são apresentadas produções visuais, literais, musicais e audiovisuais de estudantes e professores.</p>\r\n<p>Além disso, o Intervalo fala de Histórias da Bahia, da ciência presente no nosso Cotidiano e das Diversidades culturais da nossa sociedade. Gravado em escolas públicas de todo o estado da Bahia, o Intervalo foi desenvolvido em formato híbrido para internet e TV. Ao todo são 120 vídeos de 4 minutos, preparados para a publicação no Ambiente Educacional Web, que compõem 40 episódios de 13 minutos, para exibição na TV Educativa da Bahia (TVE).</p>\r\n<h4 class="menu-amarelo text-center margin-none" style="margin-top: -10px"><b>De Segunda a Sexta às 18h30 na <img src="/assets/img/logo-tve.png"></b></h4>	\N	t	2014-10-24 15:45:53.916299	1	t
25	Ilhéus	Escola Indígena Tupinambá de Olivença	24	t	2015-09-24 17:26:34.331025	1	f
31	Arraial D'Ajuda	ARRAIAL D'AJUDA	24	t	2015-10-28 09:57:52.696777	1	f
32	Jacobina	<p>Jacobina<br></p>	24	t	2015-12-10 16:45:21.571004	1	f
33	1º ANO	<p><br></p>	\N	t	2016-03-03 13:55:58.104178	2	f
36	Unidade I	<p><br></p>	33	t	2016-03-03 13:55:58.142228	2	f
37	Unidade II	<p><br></p>	33	t	2016-03-03 13:55:58.145425	2	f
38	Unidade III	<p><br></p>	33	t	2016-03-03 13:55:58.149489	2	f
39	Unidade IV	<p><br></p>	33	t	2016-03-03 13:55:58.153709	2	f
34	2º ANO	<p><br></p>	\N	t	2016-03-03 13:55:58.134359	2	f
40	Unidade I	<p><br></p>	34	t	2016-03-03 13:55:58.158205	2	f
41	Unidade II	<p><br></p>	34	t	2016-03-03 13:55:58.163393	2	f
42	Unidade III	<p><br></p>	34	t	2016-03-03 13:55:58.167899	2	f
43	Unidade IV	<p><br></p>	34	t	2016-03-03 13:55:58.17298	2	f
35	3º ANO	<p><br></p>	\N	t	2016-03-03 13:55:58.139206	2	f
44	Unidade I	<p><br></p>	35	t	2016-03-03 13:55:58.175908	2	f
45	Unidade II	<p><br></p>	35	t	2016-03-03 13:55:58.178709	2	f
46	Unidade III	<p><br></p>	35	t	2016-03-03 13:55:58.18213	2	f
47	Unidade IV	<p><br></p>	35	t	2016-03-03 13:55:58.187966	2	f
48	Senhor do Bonfim	Vídeo de conclusão do Intensivo de Interpretação e Produção de Vídeos Estudantis com alunos do Centro Juvenil de Ciências e Artes - CJCC de Senhor do Bonfim - Ba	24	t	2016-03-22 10:47:42.059688	1	f
27	Salvador	Trabalho de conclusão do Curso Intensivo de Interpretação para TV e Produção de Vídeos Estudantis, com estudantes dos colégios de Salvador e região metropolitana.	24	t	2015-10-28 08:47:41.027405	1	f
\.


--
-- TOC entry 3139 (class 0 OID 0)
-- Dependencies: 226
-- Name: conteudodigitalcategoria_idconteudodigitalcategoria_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('conteudodigitalcategoria_idconteudodigitalcategoria_seq', 48, true);


--
-- TOC entry 2849 (class 0 OID 114773)
-- Dependencies: 228 2922
-- Data for Name: conteudodigitalcomentario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY conteudodigitalcomentario (idconteudodigitalcomentario, idconteudodigital, idusuario, comentario, datacriacao) FROM stdin;
\.


--
-- TOC entry 3140 (class 0 OID 0)
-- Dependencies: 227
-- Name: conteudodigitalcomentario_idconteudodigitalcomentario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('conteudodigitalcomentario_idconteudodigitalcomentario_seq', 67, true);


--
-- TOC entry 2850 (class 0 OID 114781)
-- Dependencies: 229 2922
-- Data for Name: conteudodigitalcomponente; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY conteudodigitalcomponente (idconteudodigital, idcomponentecurricular) FROM stdin;
\.


--
-- TOC entry 2851 (class 0 OID 114784)
-- Dependencies: 230 2922
-- Data for Name: conteudodigitalfavorito; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY conteudodigitalfavorito (idconteudodigital, idfavorito) FROM stdin;
\.


--
-- TOC entry 2852 (class 0 OID 114787)
-- Dependencies: 231 2922
-- Data for Name: conteudodigitalrelacionado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY conteudodigitalrelacionado (idconteudodigital, idconteudodigitalrelacionado) FROM stdin;
\.


--
-- TOC entry 2853 (class 0 OID 114790)
-- Dependencies: 232 2922
-- Data for Name: conteudodigitaltag; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY conteudodigitaltag (idconteudodigital, idtag) FROM stdin;
\.


--
-- TOC entry 2855 (class 0 OID 114795)
-- Dependencies: 234 2922
-- Data for Name: conteudodigitalvoto; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY conteudodigitalvoto (idconteudodigitalvoto, idconteudodigital, idusuario, voto, datacriacao) FROM stdin;
\.


--
-- TOC entry 3141 (class 0 OID 0)
-- Dependencies: 233
-- Name: conteudodigitalvoto_idconteudodigitalvoto_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('conteudodigitalvoto_idconteudodigitalvoto_seq', 1211, true);


--
-- TOC entry 2856 (class 0 OID 114800)
-- Dependencies: 235 2922
-- Data for Name: conteudolicenca; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY conteudolicenca (idconteudolicenca, nomeconteudolicenca, descricaoconteudolicenca, idconteudolicencapai, siteconteudolicenca) FROM stdin;
1	Outros	Outros...	\N	\N
3	Todos direitos reservados (Copyright)	Apenas você terá autonomia para ceder ou comercializar esta obra. O arquivo desse conteúdo não será disponibilizado para download na página	\N	\N
4	Domínio público	A obra ficará livre para ser distribuída sem fins comerciais	\N	\N
5	GPL	General Public License (Licença Pública Geral), GNU GPL ou simplesmente GPL, é a designação da licença para software livre idealizada por Richard Matthew Stallman em 1989, no âmbito do projeto GNU da Free Software Foundation (FSF).	\N	http://gplv3.fsf.org/
6	Atribuição CC BY	Esta licença permite que outros distribuam, remixem, adaptem e criem a partir do seu trabalho, mesmo para fins comerciais, desde que lhe atribuam o devido crédito pela criação original. É a licença mais flexível de todas as licenças disponíveis. É recomendada para maximizar a disseminação e uso dos materiais licenciados.	2	http://creativecommons.org/licenses/by/4.0
7	Atribuição-CompartilhaIgual CC BY-SA	Esta licença permite que outros remixem, adaptem e criem a partir do seu trabalho, mesmo para fins comerciais, desde que lhe atribuam o devido crédito e que licenciem as novas criações sob termos idênticos. Esta licença costuma ser comparada com as licenças de software livre e de código aberto CopyLeft. Todos os trabalhos novos baseados no seu terão a mesma licença, portanto quaisquer trabalhos derivados também permitirão o uso comercial. Esta é a licença usada pela Wikipédia e é recomendada para materiais que seriam beneficiados com a incorporação de conteúdos da Wikipédia e de outros projetos com licenciamento semelhante.	2	http://creativecommons.org/licenses/by-sa/4.0
8	Atribuição-SemDerivações CC BY-ND	Esta licença permite a redistribuição, comercial e não comercial, desde que o trabalho seja distribuído inalterado e no seu todo, com crédito atribuído a você.	2	http://creativecommons.org/licenses/by-nd/4.0
9	Atribuição-NãoComercial CC BY-NC	Esta licença permite que outros remixem, adaptem e criem a partir do seu trabalho para fins não comerciais, e embora os novos trabalhos tenham de lhe atribuir o devido crédito e não possam ser usados para fins comerciais, os usuários não têm de licenciar esses trabalhos derivados sob os mesmos termos.	2	http://creativecommons.org/licenses/by-nc/4.0
10	Atribuição-NãoComercial-CompartilhaIgual CC BY-NC-SA	Esta licença permite que outros remixem, adaptem e criem a partir do seu trabalho para fins não comerciais, desde que atribuam a você o devido crédito e que licenciem as novas criações sob termos idênticos.	2	http://creativecommons.org/licenses/by-nc-sa/4.0
11	Atribuição-SemDerivações-SemDerivados CC BY-NC-ND	Esta é a mais restritiva das nossas seis licenças principais, só permitindo que outros façam download dos seus trabalhos e os compartilhem desde que atribuam crédito a você, mas sem que possam alterá-los de nenhuma forma ou utilizá-los para fins comerciais.	2	http://creativecommons.org/licenses/by-nc-nd/4.0
12	Atribuição CC 0	Esta licença significa que o autor que associou uma obra a este documento dedicou a obra ao domínio público, renunciando a todos os seus direitos ao trabalho em todo o mundo sob a lei de direitos de autor, incluindo todos os direitos conexos, na medida do permitido por lei, permitindo copiar, modificar, distribuir e executar a obra, mesmo para fins comerciais, sem pedir permissão. Ver Outras Informações abaixo.	2	https://creativecommons.org/choose/zero
2	Creative Commons	Qualquer pessoa poderá copiar e distribuir essa obra, desde que atribuam o crédito da mesma. O arquivo desse conteúdo será disponibilizado para download na página	\N	https://creativecommons.org
13	MIT	A permissão é concedida, gratuitamente, a qualquer pessoa que obtenha uma cópia deste software e dos arquivos de documentação associados (o "Software"), para lidar com o Software sem restrições, incluindo, sem limitação, os direitos de usar, copiar, modificar, mesclar , publicar, distribuir, sub-licenciar e / ou vender cópias do Software, e para permitir que as pessoas a quem o Software é fornecido a fazê-lo.	\N	https://opensource.org/licenses/MIT
\.


--
-- TOC entry 3142 (class 0 OID 0)
-- Dependencies: 236
-- Name: conteudolicenca_idconteudolicenca_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('conteudolicenca_idconteudolicenca_seq', 13, true);


--
-- TOC entry 2859 (class 0 OID 114810)
-- Dependencies: 238 2922
-- Data for Name: conteudotipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY conteudotipo (idconteudotipo, nomeconteudotipo) FROM stdin;
2	Planilha
3	Apresentação
4	Áudio
5	Vídeo
6	Imagem
7	Animação/Simulação
8	Site
9	Software Educacional
10	Sequência Didática
1	Documento/Experimento
\.


--
-- TOC entry 3143 (class 0 OID 0)
-- Dependencies: 237
-- Name: conteudotipo_idconteudotipo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('conteudotipo_idconteudotipo_seq', 10, true);


--
-- TOC entry 2861 (class 0 OID 114816)
-- Dependencies: 240 2922
-- Data for Name: denuncia; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY denuncia (iddenuncia, idusuario, url, mensagem, datacriacao, titulo, flvisualizada) FROM stdin;
\.


--
-- TOC entry 3144 (class 0 OID 0)
-- Dependencies: 239
-- Name: denuncia_iddenuncia_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('denuncia_iddenuncia_seq', 55, true);


--
-- TOC entry 2862 (class 0 OID 114824)
-- Dependencies: 241 2922
-- Data for Name: dispositivo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY dispositivo (iddispositivo, nomedispositivo, datacriacao) FROM stdin;
1	desktop	2015-11-25 09:46:10.270949
2	tablet	2015-11-25 09:46:10.302193
3	mobile	2015-11-25 09:46:10.333422
\.


--
-- TOC entry 3145 (class 0 OID 0)
-- Dependencies: 242
-- Name: dispositivo_iddispositivo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('dispositivo_iddispositivo_seq', 3, true);


--
-- TOC entry 2865 (class 0 OID 114832)
-- Dependencies: 244 2922
-- Data for Name: enquete; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY enquete (idenquete, idcomunidade, idusuario, pergunta, datainicio, datafim) FROM stdin;
\.


--
-- TOC entry 3146 (class 0 OID 0)
-- Dependencies: 243
-- Name: enquete_idenquete_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('enquete_idenquete_seq', 11, true);


--
-- TOC entry 2867 (class 0 OID 114839)
-- Dependencies: 246 2922
-- Data for Name: enqueteopcao; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY enqueteopcao (idenqueteopcao, idenquete, opcao) FROM stdin;
\.


--
-- TOC entry 3147 (class 0 OID 0)
-- Dependencies: 245
-- Name: enqueteopcao_idenqueteopcao_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('enqueteopcao_idenqueteopcao_seq', 42, true);


--
-- TOC entry 2869 (class 0 OID 114845)
-- Dependencies: 248 2922
-- Data for Name: enqueteopcaoresposta; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY enqueteopcaoresposta (idenqueteopcaoresposta, idenqueteopcao, idusuario, idenquete) FROM stdin;
\.


--
-- TOC entry 3148 (class 0 OID 0)
-- Dependencies: 247
-- Name: enqueteopcaoresposta_idenqueteopcaoresposta_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('enqueteopcaoresposta_idenqueteopcaoresposta_seq', 21, true);


--
-- TOC entry 2871 (class 0 OID 114851)
-- Dependencies: 250 2922
-- Data for Name: escola; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY escola (idescola, idmunicipio, nomeescola, codigomec) FROM stdin;
1	1	EEPF Escola teste	41284
\.


--
-- TOC entry 3149 (class 0 OID 0)
-- Dependencies: 249
-- Name: escola_idescola_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('escola_idescola_seq', 2, true);


--
-- TOC entry 2873 (class 0 OID 114857)
-- Dependencies: 252 2922
-- Data for Name: estado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY estado (idestado, nomeestado, codigoibgesiig) FROM stdin;
2	Não Informado	
2116	BA	29
2573	AC	12
2574	AL	27
2575	AP	16
2576	AM	13
2577	CE	23
2578	DF	53
2579	ES	32
2580	GO	52
2581	MA	21
2582	MT	51
2583	MS	50
2584	MG	31
2585	PA	15
2586	PB	25
2587	PR	41
2588	PE	26
2589	PI	22
2590	RJ	33
2591	RN	24
2592	RS	43
2593	RO	11
2594	RR	14
2595	SC	42
2596	SP	35
2597	SE	28
2598	TO	17
\.


--
-- TOC entry 3150 (class 0 OID 0)
-- Dependencies: 251
-- Name: estado_idestado_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('estado_idestado_seq', 8, true);


--
-- TOC entry 2875 (class 0 OID 114863)
-- Dependencies: 254 2922
-- Data for Name: favorito; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY favorito (idfavorito) FROM stdin;
1
2
3
4
5
6
\.


--
-- TOC entry 3151 (class 0 OID 0)
-- Dependencies: 253
-- Name: favorito_idfavorito_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('favorito_idfavorito_seq', 2770, true);


--
-- TOC entry 2876 (class 0 OID 114867)
-- Dependencies: 255 2922
-- Data for Name: feedcontagem; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY feedcontagem (idfeedcontagem, idusuario, qtd_feed_recados, qtd_feed_colegas, qtd_feed_comunidades, qtd_feed_albuns, qtd_feed_agenda, qtd_feed_blog, datacriacao, flacesso, dataacesso, flchatativo, iddispositivo) FROM stdin;
\.


--
-- TOC entry 3152 (class 0 OID 0)
-- Dependencies: 256
-- Name: feedcontagem_idfeedcontagem_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('feedcontagem_idfeedcontagem_seq', 2350, true);


--
-- TOC entry 2878 (class 0 OID 114882)
-- Dependencies: 257 2922
-- Data for Name: feeddetalhe; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY feeddetalhe (id, idusuarioremetente, idusuariodestinatario, idfeedtabela, idfeedmensagem, idregistrotabela, valorantigo, valornovo, datacriacao, idcomunidade) FROM stdin;
\.


--
-- TOC entry 3153 (class 0 OID 0)
-- Dependencies: 258
-- Name: feeddetalhe_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('feeddetalhe_id_seq', 12472, true);


--
-- TOC entry 2880 (class 0 OID 114888)
-- Dependencies: 259 2922
-- Data for Name: feedtabela; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY feedtabela (id, nome, status) FROM stdin;
1	ambientedeapoiofavorito	t
2	blogcomentario	t
3	comunidadealbum	t
4	comunidadealbumfoto	t
5	comunidadeblog	t
6	comunidadesugerida	t
7	conteudodigitalfavorito	t
8	enquete	t
9	marcacaoagenda	t
11	usuarioalbumfoto	t
12	usuarioblog	t
13	usuariocolega	t
14	usuariorecado	t
10	usuarioalbum	t
15	usuarioagenda	t
16	comunidadeagenda	t
17	comuusuario	t
18	comutopico	t
19	albumcomentario	t
20	comutopicomsg	t
21	comunidade	t
\.


--
-- TOC entry 3154 (class 0 OID 0)
-- Dependencies: 260
-- Name: feedtabela_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('feedtabela_id_seq', 1, false);


--
-- TOC entry 2882 (class 0 OID 114894)
-- Dependencies: 261 2922
-- Data for Name: feedtipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY feedtipo (id, nomefeedtipo) FROM stdin;
1	[idusuarioremetente] criou nova postagem no blog [idblog]
2	[idusuarioremetente] comentou postagem no blog [idblog] de [idusuariodestinatario]
3	Foi criada uma nova postagem de blog [idblog] na comunidade [idcomunidade]
4	[idusuarioremetente] comentou postagem no blog [idblog] da comunidade [idcomunidade]
5	[idusuarioremetente] enviou um novo recado para você
6	[idusuarioremetente] deseja fazer parte de sua rede
7	[idusuarioremetente] passou a fazer parte da rede de [idusuariodestinatario]
8	[idusuarioremetente] convidou você para a comunidade [idcomunidade]
9	[idusuarioremetente] passou a fazer parte da comunidade [idcomunidade]
10	[idusuarioremetente] convidou você para participar do evento [idagenda]
11	[idusuarioremetente] aceitou convite para o evento [idagenda]
12	[idusuarioremetente] adicionou o conteúdo [idconteudo] como favorito
13	O conteúdo [idconteudo] foi adicionado como favorito na comunidade [idcomunidade]
14	O álbum [idalbum] foi adicionado por [idusuarioremetente]
15	O álbum [idalbum] foi adicionado na comunidade [idcomunidade]
16	[idusuarioremetente] criou tópico [idtopico] no fórum da comunidade [idcomunidade]
17	Nova enquete foi criada na comunidade [idcomunidade]
18	Nova foto foi adicionada no álbum [idalbum] da comunidade [idcomunidade]
19	Nova foto foi adicionada no álbum [idalbum] por [idusuarioremetente]
20	[idusuarioremetente] criou o evento [idagenda]
21	O evento [idagenda] foi criado na comunidade [idcomunidade]
22	Você foi convidado para participar do evento [idagenda] na comunidade [idcomunidade]
23	[idusuarioremetente] aceitou convite para o evento [idagenda] da comunidade [idcomunidade]
24	[idusuarioremetente] comentou postagem no blog [idblog]
25	[idusuarioremetente] criou a nova comunidade [idcomunidade]
\.


--
-- TOC entry 3155 (class 0 OID 0)
-- Dependencies: 262
-- Name: feedtipo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('feedtipo_id_seq', 1, false);


--
-- TOC entry 2885 (class 0 OID 114901)
-- Dependencies: 264 2922
-- Data for Name: formato; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY formato (idformato, nomeformato, idconteudotipo) FROM stdin;
1	pdf	1
2	doc	1
3	docx	1
5	htm	1
6	rtf	1
7	txt	1
4	html	1
8	xls	2
9	xml	2
10	ods	2
11	csv	2
12	ppt	3
13	pps	3
14	odp	3
15	mp3	4
16	wma	4
17	wav	4
18	wmv	5
19	mov	5
20	mpg	5
21	flv	5
22	avi	5
23	rmvb	5
24	jpg	6
25	png	6
26	gif	6
27	odg	6
28	swf	7
29	exe	7
30	zip	7
31	rar	7
32	odt	1
33	jpeg	6
34	jpeg	6
35	link	8
36	link	1
37	link	2
38	link	3
39	link	4
40	link	5
41	link	6
42	link	7
43	youtube	5
44	link	9
45	link	10
46	mp4	5
47	webm	5
48	pdf	10
49	doc	10
\.


--
-- TOC entry 3156 (class 0 OID 0)
-- Dependencies: 263
-- Name: formato_idformato_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('formato_idformato_seq', 49, true);


--
-- TOC entry 2886 (class 0 OID 114905)
-- Dependencies: 265 2922
-- Data for Name: marcacaoagenda; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY marcacaoagenda (idagenda, idusuario, tipo, visto, aceito, datacriacao) FROM stdin;
\.


--
-- TOC entry 2888 (class 0 OID 114911)
-- Dependencies: 267 2922
-- Data for Name: municipio; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY municipio (idmunicipio, idestado, nomemunicipio, codigoibgesiig) FROM stdin;
3	2589	NAZARIA	2206720
2119	2116	Baianopolis	2902500
2120	2116	Barreiras	2903201
2121	2116	Catolandia	2907400
2122	2116	Formosa do Rio Preto	2911105
2123	2116	Luis Eduardo Magalhães	2919553
2124	2116	Riachao das Neves	2926202
2125	2116	Sao Desiderio	2928901
2127	2116	Angical	2901403
2128	2116	Brejolandia	2904407
2129	2116	Cotegipe	2909406
2130	2116	Cristopolis	2909703
2131	2116	Mansidao	2920452
2132	2116	Santa Rita de Cassia	2928406
2133	2116	Tabocas do Brejo Velho	2930907
2134	2116	Wanderley	2933455
2136	2116	Canapolis	2906105
2137	2116	Cocos	2908101
2138	2116	Coribe	2909109
2139	2116	Correntina	2909307
2140	2116	Jaborandi	2917359
2141	2116	Santa Maria da Vitoria	2928109
2142	2116	Santana	2928208
2143	2116	Sao Felix do Coribe	2929057
2144	2116	Serra Dourada	2930303
2147	2116	Campo Alegre de Lourdes	2905909
2148	2116	Casa Nova	2907202
2149	2116	Curaca	2909901
2150	2116	Juazeiro	2918407
2151	2116	Pilao Arcado	2924405
2152	2116	Remanso	2926004
2153	2116	Sento Se	2930204
2154	2116	Sobradinho	2930774
2156	2116	Abare	2900207
2157	2116	Chorrocho	2907707
2158	2116	Gloria	2911402
2159	2116	Macurure	2919900
2160	2116	Paulo Afonso	2924009
2161	2116	Rodelas	2927101
2163	2116	Barra	2902708
2164	2116	Buritirama	2904753
2165	2116	Ibotirama	2913200
2166	2116	Itaguacu da Bahia	2915353
2167	2116	Morpara	2921609
2168	2116	Muquem de Sao Francisco	2922250
2169	2116	Xique-Xique	2933604
2171	2116	Bom Jesus da Lapa	2903904
2172	2116	Carinhanha	2907103
2173	2116	Feira da Mata	2910776
2174	2116	Paratinga	2923704
2175	2116	Serra do Ramalho	2930154
2176	2116	Sitio do Mato	2930758
2179	2116	Andorinha	2901353
2180	2116	Antonio Goncalves	2901809
2181	2116	Campo Formoso	2906006
2182	2116	Filadelfia	2910859
2183	2116	Itiuba	2917003
2184	2116	Jaguarari	2917706
2185	2116	Pindobacu	2924603
2186	2116	Senhor do Bonfim	2930105
2187	2116	Umburanas	2932457
2189	2116	America Dourada	2901155
2190	2116	Barra do Mendes	2903003
2191	2116	Barro Alto	2903235
2192	2116	Cafarnaum	2905305
2193	2116	Canarana	2906204
2194	2116	Central	2907608
2195	2116	Gentio do Ouro	2911303
2196	2116	Ibipeba	2912400
2197	2116	Ibitita	2913101
2198	2116	Iraquara	2914406
2199	2116	Irece	2914604
2200	2116	Joao Dourado	2918357
2201	2116	Jussara	2918506
2202	2116	Lapao	2919157
2203	2116	Mulungu do Morro	2922052
2204	2116	Presidente Dutra	2925600
2205	2116	Sao Gabriel	2929255
2206	2116	Souto Soares	2930808
2207	2116	Uibai	2932408
2209	2116	Caem	2905107
2210	2116	Caldeirao Grande	2905503
2211	2116	Capim Grosso	2906873
2212	2116	Jacobina	2917508
2213	2116	Miguel Calmon	2921203
2214	2116	Mirangaba	2921401
2215	2116	Morro do Chapeu	2921708
2216	2116	Ourolandia	2923357
2217	2116	Piritiba	2924801
2218	2116	Ponto Novo	2925253
2219	2116	Quixabeira	2925931
2220	2116	Sao Jose do Jacuipe	2929370
2221	2116	Saude	2929800
2222	2116	Serrolandia	2930600
2223	2116	Varzea do Poco	2933109
2224	2116	Varzea Nova	2933158
2226	2116	Baixa Grande	2902609
2227	2116	Boa Vista do Tupim	2903805
2228	2116	Iacu	2911907
2229	2116	Ibiquera	2912608
2230	2116	Itaberaba	2914703
2231	2116	Lajedinho	2919009
2232	2116	Macajuba	2919603
2233	2116	Mairi	2920106
2234	2116	Mundo Novo	2922102
2235	2116	Ruy Barbosa	2927200
2236	2116	Tapiramuta	2931301
2237	2116	Varzea da Roca	2933059
2239	2116	Agua Fria	2900405
2240	2116	Anguera	2901502
2241	2116	Antonio Cardoso	2901700
2242	2116	Conceicao da Feira	2908200
2243	2116	Conceicao do Jacuipe	2908507
2244	2116	Coracao de Maria	2908903
2245	2116	Elisio Medrado	2910305
2246	2116	Feira de Santana	2910800
2247	2116	Ipecaeta	2913804
2248	2116	Ipira	2914000
2249	2116	Irara	2914505
2250	2116	Itatim	2916856
2251	2116	Ouricangas	2923308
2252	2116	Pedrao	2924108
2253	2116	Pintadas	2924652
2254	2116	Rafael Jambeiro	2925956
2255	2116	Santa Barbara	2927507
2256	2116	Santanopolis	2928307
2257	2116	Santa Teresinha	2928505
2258	2116	Santo Estevao	2928802
2259	2116	Sao Goncalo dos Campos	2929305
2260	2116	Serra Preta	2930402
2261	2116	Tanquinho	2931103
2262	2116	Teodoro Sampaio	2931400
2265	2116	Coronel Joao Sa	2909208
2266	2116	Jeremoabo	2918100
2267	2116	Pedro Alexandre	2924207
2268	2116	Santa Brigida	2927606
2269	2116	Sitio do Quinto	2930766
2271	2116	Cansancao	2906808
2272	2116	Canudos	2906824
2273	2116	Euclides da Cunha	2910701
2274	2116	Monte Santo	2921500
2275	2116	Nordestina	2922656
2276	2116	Queimadas	2925808
2277	2116	Quijingue	2925907
2278	2116	Tucano	2931905
2279	2116	Uaua	2932002
2281	2116	Adustina	2900355
2282	2116	Antas	2901601
2283	2116	Banzae	2902658
2284	2116	Cicero Dantas	2907806
2285	2116	Cipo	2907905
2286	2116	Fatima	2910750
2287	2116	Heliopolis	2911857
2288	2116	Itapicuru	2916500
2289	2116	Nova Soure	2922904
2290	2116	Novo Triunfo	2923050
2291	2116	Olindina	2923100
2292	2116	Paripiranga	2923803
2293	2116	Ribeira do Amparo	2926509
2294	2116	Ribeira do Pombal	2926608
2296	2116	Araci	2902104
2297	2116	Barrocas	2903276
2298	2116	Biritinga	2903607
2299	2116	Candeal	2906402
2300	2116	Capela do Alto Alegre	2906857
2301	2116	Conceicao do Coite	2908408
2302	2116	Gaviao	2911253
2303	2116	Ichu	2913309
2304	2116	Lamarao	2919108
2305	2116	Nova Fatima	2922730
2306	2116	Pe de Serra	2924058
2307	2116	Retirolandia	2926103
2308	2116	Riachao do Jacuipe	2926301
2309	2116	Santaluz	2928000
2310	2116	Sao Domingos	2928950
2311	2116	Serrinha	2930501
2312	2116	Teofilandia	2931509
2313	2116	Valente	2933000
2315	2116	Acajutiba	2900306
2316	2116	Alagoinhas	2900702
2317	2116	Apora	2901908
2318	2116	Aracas	2902054
2319	2116	Aramari	2902203
2320	2116	Crisopolis	2909604
2321	2116	Inhambupe	2913705
2322	2116	Rio Real	2927002
2323	2116	Satiro Dias	2929701
2325	2116	Cardeal da Silva	2907004
2326	2116	Conde	2908606
2327	2116	Entre Rios	2910503
2328	2116	Esplanada	2910602
2329	2116	Jandaira	2917904
2332	2116	Amelia Rodrigues	2901106
2333	2116	Catu	2907509
2334	2116	Itanagra	2915908
2335	2116	Mata de Sao Joao	2921005
2336	2116	Pojuca	2925204
2337	2116	Sao Sebastiao do Passe	2929503
2338	2116	Terra Nova	2931707
2340	2116	Aratuipe	2902302
2341	2116	Cabaceiras do Paraguacu	2904852
2342	2116	Cachoeira	2904902
2343	2116	Castro Alves	2907301
2344	2116	Conceicao do Almeida	2908309
2345	2116	Cruz das Almas	2909802
2346	2116	Dom Macedo Costa	2910206
2347	2116	Governador Mangabeira	2911600
2348	2116	Jaguaripe	2917805
2349	2116	Maragogipe	2920601
2350	2116	Muniz Ferreira	2922201
2351	2116	Muritiba	2922300
2352	2116	Nazare	2922508
2353	2116	Salinas da Margarida	2927309
2354	2116	Santo Amaro	2928604
2355	2116	Santo Antonio de Jesus	2928703
2356	2116	Sao Felix	2929008
2357	2116	Sao Felipe	2929107
2358	2116	Sapeacu	2929602
2359	2116	Saubara	2929750
2360	2116	Varzedo	2933174
2362	2116	Camacari	2905701
2363	2116	Candeias	2906501
2364	2116	Dias dAvila	2910057
2365	2116	Itaparica	2916104
2366	2116	Lauro de Freitas	2919207
2367	2116	Madre de Deus	2919926
2368	2116	Salvador	2927408
2369	2116	Sao Francisco do Conde	2929206
2370	2116	Simoes Filho	2930709
2371	2116	Vera Cruz	2933208
2374	2116	Boquira	2904100
2375	2116	Botupora	2904209
2376	2116	Brotas de Macaubas	2904506
2377	2116	Caturama	2907558
2378	2116	Ibipitanga	2912509
2379	2116	Ibitiara	2913002
2380	2116	Ipupiara	2914109
2381	2116	Macaubas	2919801
2382	2116	Novo Horizonte	2923035
2383	2116	Oliveira dos Brejinhos	2923209
2384	2116	Tanque Novo	2931053
2386	2116	Abaira	2900108
2387	2116	Andarai	2901304
2388	2116	Barra da Estiva	2902807
2389	2116	Boninal	2904001
2390	2116	Bonito	2904050
2391	2116	Contendas do Sincora	2908804
2392	2116	Ibicoara	2912202
2393	2116	Itaete	2915007
2394	2116	Jussiape	2918605
2395	2116	Lencois	2919306
2396	2116	Mucuge	2921906
2397	2116	Nova Redencao	2922854
2398	2116	Palmeiras	2923506
2399	2116	Piata	2924306
2400	2116	Rio de Contas	2926707
2401	2116	Seabra	2929909
2402	2116	Utinga	2932804
2403	2116	Wagner	2933406
2405	2116	Aiquara	2900603
2406	2116	Amargosa	2901007
2407	2116	Apuarema	2901957
2408	2116	Brejoes	2904308
2409	2116	Cravolandia	2909505
2410	2116	Irajuba	2914208
2411	2116	Iramaia	2914307
2412	2116	Itagi	2915106
2413	2116	Itaquara	2916708
2414	2116	Itirucu	2916906
2415	2116	Jaguaquara	2917607
2416	2116	Jequie	2918001
2417	2116	Jiquirica	2918209
2418	2116	Jitauna	2918308
2419	2116	Lafaiete Coutinho	2918704
2420	2116	Laje	2918803
2421	2116	Lajedo do Tabocal	2919058
2422	2116	Maracas	2920502
2423	2116	Marcionilio Souza	2920809
2424	2116	Milagres	2921302
2425	2116	Mutuipe	2922409
2426	2116	Nova Itarana	2922805
2427	2116	Planaltino	2924900
2428	2116	Santa Ines	2927903
2429	2116	Sao Miguel das Matas	2929404
2430	2116	Ubaira	2932101
2432	2116	Erico Cardoso	2900504
2433	2116	Dom Basilio	2910107
2434	2116	Livramento de Nsa. Senhora	2919504
2435	2116	Paramirim	2923605
2436	2116	Rio do Pires	2926905
2438	2116	Cacule	2905008
2439	2116	Caetite	2905206
2440	2116	Candiba	2906600
2441	2116	Guanambi	2911709
2442	2116	Ibiassuce	2912004
2443	2116	Igapora	2913408
2444	2116	Iuiu	2917334
2445	2116	Jacaraci	2917409
2446	2116	Lagoa Real	2918753
2447	2116	Licinio de Almeida	2919405
2448	2116	Malhada	2920205
2449	2116	Matina	2921054
2450	2116	Mortugaba	2921807
2451	2116	Palmas de Monte Alto	2923407
2452	2116	Pindai	2924504
2453	2116	Riacho de Santana	2926400
2454	2116	Sebastiao Laranjeiras	2930006
2455	2116	Urandi	2932606
2457	2116	Aracatu	2902005
2458	2116	Brumado	2904605
2459	2116	Caraibas	2906899
2460	2116	Condeuba	2908705
2461	2116	Cordeiros	2909000
2462	2116	Guajeru	2911659
2463	2116	Ituacu	2917201
2464	2116	Maetinga	2919959
2465	2116	Malhada de Pedras	2920304
2466	2116	Piripa	2924702
2467	2116	Presidente Janio Quadros	2925709
2468	2116	Rio do Antonio	2926806
2469	2116	Tanhacu	2931004
2470	2116	Tremedal	2931806
2472	2116	Anage	2901205
2473	2116	Barra do Choca	2902906
2474	2116	Belo Campo	2903508
2475	2116	Boa Nova	2903706
2476	2116	Bom Jesus da Serra	2903953
2477	2116	Caatiba	2904803
2478	2116	Caetanos	2905156
2479	2116	Candido Sales	2906709
2480	2116	Dario Meira	2910008
2481	2116	Ibicui	2912301
2482	2116	Iguai	2913507
2483	2116	Manoel Vitorino	2920403
2484	2116	Mirante	2921450
2485	2116	Nova Canaa	2922706
2486	2116	Planalto	2925006
2487	2116	Pocoes	2925105
2488	2116	Vitoria da Conquista	2933307
2490	2116	Encruzilhada	2910404
2491	2116	Itambe	2915809
2492	2116	Itapetinga	2916401
2493	2116	Itarantim	2916807
2494	2116	Itororo	2917102
2495	2116	Macarani	2919702
2496	2116	Maiquinique	2920007
2497	2116	Potiragua	2925402
2498	2116	Ribeirao do Largo	2926657
2501	2116	Cairu	2905404
2502	2116	Camamu	2905800
2503	2116	Igrapiuna	2913457
2504	2116	Itubera	2917300
2505	2116	Marau	2920700
2506	2116	Nilo Pecanha	2922607
2507	2116	Pirai do Norte	2924678
2508	2116	Presidente Tancredo Neves	2925758
2509	2116	Taperoa	2931202
2510	2116	Valenca	2932903
2512	2116	Almadina	2900900
2513	2116	Arataca	2902252
2514	2116	Aurelino Leal	2902401
2515	2116	Barra do Rocha	2903102
2516	2116	Lomanto Junior  (Barro Preto)	2903300
2517	2116	Belmonte	2903409
2518	2116	Buerarema	2904704
2519	2116	Camacan	2905602
2520	2116	Canavieiras	2906303
2521	2116	Coaraci	2908002
2522	2116	Firmino Alves	2910909
2523	2116	Floresta Azul	2911006
2524	2116	Gandu	2911204
2525	2116	Gongogi	2911501
2526	2116	Ibicarai	2912103
2527	2116	Ibirapitanga	2912707
2528	2116	Ibirataia	2912905
2529	2116	Ilheus	2913606
2530	2116	Ipiau	2913903
2531	2116	Itabuna	2914802
2532	2116	Itacare	2914901
2533	2116	Itagiba	2915205
2534	2116	Itaju do Colonia	2915403
2535	2116	Itajuipe	2915502
2536	2116	Itamari	2915700
2537	2116	Itape	2916203
2538	2116	Itapebi	2916302
2539	2116	Itapitanga	2916609
2540	2116	Jussari	2918555
2541	2116	Mascote	2920908
2542	2116	Nova Ibia	2922755
2543	2116	Pau Brasil	2923902
2544	2116	Santa Cruz da Vitoria	2927804
2545	2116	Santa Luzia	2928059
2546	2116	Sao Jose da Vitoria	2929354
2547	2116	Teolandia	2931608
2548	2116	Ubaitaba	2932200
2549	2116	Ubata	2932309
2550	2116	Una	2932507
2551	2116	Urucuca	2932705
2552	2116	Wenceslau Guimaraes	2933505
2554	2116	Alcobaca	2900801
2555	2116	Caravelas	2906907
2556	2116	Eunapolis	2910727
2557	2116	Guaratinga	2911808
2558	2116	Ibirapua	2912806
2559	2116	Itabela	2914653
2560	2116	Itagimirim	2915304
2561	2116	Itamaraju	2915601
2562	2116	Itanhem	2916005
2563	2116	Jucurucu	2918456
2564	2116	Lajedao	2918902
2565	2116	Medeiros Neto	2921104
2566	2116	Mucuri	2922003
2567	2116	Nova Vicosa	2923001
2568	2116	Porto Seguro	2925303
2569	2116	Prado	2925501
2570	2116	Santa Cruz Cabralia	2927705
2571	2116	Teixeira de Freitas	2931350
2572	2116	Vereda	2933257
5148	2593	Alta Floresta D'Oeste	1100015
5149	2593	Ariquemes	1100023
5150	2593	Cabixi	1100031
5151	2593	Cacoal	1100049
5152	2593	Cerejeiras	1100056
5153	2593	Colorado do Oeste	1100064
5154	2593	Corumbiara	1100072
5155	2593	Costa Marques	1100080
5156	2593	Espigao D'Oeste	1100098
5157	2593	Guajara-Mirim	1100106
5158	2593	Jaru	1100114
5159	2593	Ji-Parana	1100122
5160	2593	Machadinho D'Oeste	1100130
5161	2593	Nova Brasilandia D'Oeste	1100148
5162	2593	Ouro Preto do Oeste	1100155
5163	2593	Pimenta Bueno	1100189
5164	2593	Porto Velho	1100205
5165	2593	Presidente Medici	1100254
5166	2593	Rio Crespo	1100262
5167	2593	Rolim de Moura	1100288
5168	2593	Santa Luzia D'Oeste	1100296
5169	2593	Vilhena	1100304
5170	2593	Sao Miguel do Guapore	1100320
5171	2593	Nova Mamore	1100338
5172	2593	Alvorada D'Oeste	1100346
5173	2593	Alto Alegre do Parecis	1100379
5174	2593	Alto Paraiso	1100403
5175	2593	Buritis	1100452
5176	2593	Novo Horizonte do Oeste	1100502
5177	2593	Cacaulandia	1100601
5178	2593	Campo Novo de Rondonia	1100700
5179	2593	Candeias do Jamari	1100809
5180	2593	Castanheiras	1100908
5181	2593	Chupinguaia	1100924
5182	2593	Cujubim	1100940
5183	2593	Governador Jorge Teixeira	1101005
5184	2593	Jamari	1101104
5185	2593	Ministro Andreazza	1101203
5186	2593	Mirante da Serra	1101302
5187	2593	Monte Negro	1101401
5188	2593	Nova Uniao	1101435
5189	2593	Parecis	1101450
5190	2593	Pimenteiras do Oeste	1101468
5191	2593	Primavera de Rondonia	1101476
5192	2593	Sao Felipe D'Oeste	1101484
5193	2593	Sao Francisco do Guapore	1101492
5194	2593	Seringueiras	1101500
5195	2593	Teixeiropolis	1101559
5196	2593	Theobroma	1101609
5197	2593	Urupa	1101708
5198	2593	Vale do Anari	1101757
5199	2593	Vale do Paraiso	1101807
5200	2573	Acrelandia	1200013
5201	2573	Assis Brasil	1200054
5202	2573	Brasileia	1200104
5203	2573	Bujari	1200138
5204	2573	Capixaba	1200179
5205	2573	Cruzeiro do Sul	1200203
5206	2573	Epitaciolandia	1200252
5207	2573	Feijo	1200302
5208	2573	Jordao	1200328
5209	2573	Mancio Lima	1200336
5210	2573	Manoel Urbano	1200344
5211	2573	Marechal Thaumaturgo	1200351
5212	2573	Placido de Castro	1200385
5213	2573	Porto Walter	1200393
5214	2573	Rio Branco	1200401
5215	2573	Rodrigues Alves	1200427
5216	2573	Santa Rosa do Purus	1200435
5217	2573	Senador Guiomard	1200450
5218	2573	Sena Madureira	1200500
5219	2573	Tarauaca	1200609
5220	2573	Xapuri	1200708
5221	2573	Porto Acre	1200807
5222	2576	Alvaraes	1300029
5223	2576	Amatura	1300060
5224	2576	Anama	1300086
5225	2576	Anori	1300102
5226	2576	Apui	1300144
5227	2576	Atalaia do Norte	1300201
5228	2576	Autazes	1300300
5229	2576	Barcelos	1300409
5230	2576	Barreirinha	1300508
5231	2576	Benjamin Constant	1300607
5232	2576	Beruri	1300631
5233	2576	Boa Vista do Ramos	1300680
5234	2576	Boca do Acre	1300706
5235	2576	Borba	1300805
5236	2576	Caapiranga	1300839
5237	2576	Canutama	1300904
5238	2576	Carauari	1301001
5239	2576	Careiro	1301100
5240	2576	Careiro da Varzea	1301159
5241	2576	Coari	1301209
5242	2576	Codajas	1301308
5243	2576	Eirunepe	1301407
5244	2576	Envira	1301506
5245	2576	Fonte Boa	1301605
5246	2576	Guajara	1301654
5247	2576	Humaita	1301704
5248	2576	Ipixuna	1301803
5249	2576	Iranduba	1301852
5250	2576	Itacoatiara	1301902
5251	2576	Itamarati	1301951
5252	2576	Itapiranga	1302009
5253	2576	Japura	1302108
5254	2576	Jurua	1302207
5255	2576	Jutai	1302306
5256	2576	Labrea	1302405
5257	2576	Manacapuru	1302504
5258	2576	Manaquiri	1302553
5259	2576	Manaus	1302603
5260	2576	Manicore	1302702
5261	2576	Maraa	1302801
5262	2576	Maues	1302900
5263	2576	Nhamunda	1303007
5264	2576	Nova Olinda do Norte	1303106
5265	2576	Novo Airao	1303205
5266	2576	Novo Aripuana	1303304
5267	2576	Parintins	1303403
5268	2576	Pauini	1303502
5269	2576	Presidente Figueiredo	1303536
5270	2576	Rio Preto da Eva	1303569
5271	2576	Santa Isabel do Rio Negro	1303601
5272	2576	Santo Antonio do Ica	1303700
5273	2576	Sao Gabriel da Cachoeira	1303809
5274	2576	Sao Paulo de Olivenca	1303908
5275	2576	Sao Sebastiao do Uatuma	1303957
5276	2576	Silves	1304005
5277	2576	Tabatinga	1304062
5278	2576	Tapaua	1304104
5279	2576	Tefe	1304203
5280	2576	Tonantins	1304237
5281	2576	Uarini	1304260
5282	2576	Urucara	1304302
5283	2576	Urucurituba	1304401
5284	2594	Amajari	1400027
5285	2594	Alto Alegre	1400050
5286	2594	Boa Vista	1400100
5287	2594	Bonfim	1400159
5288	2594	Canta	1400175
5289	2594	Caracarai	1400209
5290	2594	Caroebe	1400233
5291	2594	Iracema	1400282
5292	2594	Mucajai	1400308
5293	2594	Normandia	1400407
5294	2594	Pacaraima	1400456
5295	2594	Rorainopolis	1400472
5296	2594	Sao Joao da Baliza	1400506
5297	2594	Sao Luiz	1400605
5298	2594	Uiramuta	1400704
5299	2585	Abaetetuba	1500107
5300	2585	Abel Figueiredo	1500131
5301	2585	Acara	1500206
5302	2585	Afua	1500305
5303	2585	Agua Azul do Norte	1500347
5304	2585	Alenquer	1500404
5305	2585	Almeirim	1500503
5306	2585	Altamira	1500602
5307	2585	Anajas	1500701
5308	2585	Ananindeua	1500800
5309	2585	Anapu	1500859
5310	2585	Augusto Correa	1500909
5311	2585	Aurora do Para	1500958
5312	2585	Aveiro	1501006
5313	2585	Bagre	1501105
5314	2585	Baiao	1501204
5315	2585	Bannach	1501253
5316	2585	Barcarena	1501303
5317	2585	Belem	1501402
5318	2585	Belterra	1501451
5319	2585	Benevides	1501501
5320	2585	Bom Jesus do Tocantins	1501576
5321	2585	Bonito	1501600
5322	2585	Braganca	1501709
5323	2585	Brasil Novo	1501725
5324	2585	Brejo Grande do Araguaia	1501758
5325	2585	Breu Branco	1501782
5326	2585	Breves	1501808
5327	2585	Bujaru	1501907
5328	2585	Cachoeira do Piria	1501956
5329	2585	Cachoeira do Arari	1502004
5330	2585	Cameta	1502103
5331	2585	Canaa dos Carajas	1502152
5332	2585	Capanema	1502202
5333	2585	Capitao Poco	1502301
5334	2585	Castanhal	1502400
5335	2585	Chaves	1502509
5336	2585	Colares	1502608
5337	2585	Conceicao do Araguaia	1502707
5338	2585	Concordia do Para	1502756
5339	2585	Cumaru do Norte	1502764
5340	2585	Curionopolis	1502772
5341	2585	Curralinho	1502806
5342	2585	Curua	1502855
5343	2585	Curuca	1502905
5344	2585	Dom Eliseu	1502939
5345	2585	Eldorado dos Carajas	1502954
5346	2585	Faro	1503002
5347	2585	Floresta do Araguaia	1503044
5348	2585	Garrafao do Norte	1503077
5349	2585	Goianesia do Para	1503093
5350	2585	Gurupa	1503101
5351	2585	Igarape-Acu	1503200
5352	2585	Igarape-Miri	1503309
5353	2585	Inhangapi	1503408
5354	2585	Ipixuna do Para	1503457
5355	2585	Irituia	1503507
5356	2585	Itaituba	1503606
5357	2585	Itupiranga	1503705
5358	2585	Jacareacanga	1503754
5359	2585	Jacunda	1503804
5360	2585	Juruti	1503903
5361	2585	Limoeiro do Ajuru	1504000
5362	2585	Mae do Rio	1504059
5363	2585	Magalhaes Barata	1504109
5364	2585	Maraba	1504208
5365	2585	Maracana	1504307
5366	2585	Marapanim	1504406
5367	2585	Marituba	1504422
5368	2585	Medicilandia	1504455
5369	2585	Melgaco	1504505
5370	2585	Mocajuba	1504604
5371	2585	Moju	1504703
5372	2585	Monte Alegre	1504802
5373	2585	Muana	1504901
5374	2585	Nova Esperanca do Piria	1504950
5375	2585	Nova Ipixuna	1504976
5376	2585	Nova Timboteua	1505007
5377	2585	Novo Progresso	1505031
5378	2585	Novo Repartimento	1505064
5379	2585	Obidos	1505106
5380	2585	Oeiras do Para	1505205
5381	2585	Oriximina	1505304
5382	2585	Ourem	1505403
5383	2585	Ourilandia do Norte	1505437
5384	2585	Pacaja	1505486
5385	2585	Palestina do Para	1505494
5386	2585	Paragominas	1505502
5387	2585	Parauapebas	1505536
5388	2585	Pau D'Arco	1505551
5389	2585	Peixe-Boi	1505601
5390	2585	Picarra	1505635
5391	2585	Placas	1505650
5392	2585	Ponta de Pedras	1505700
5393	2585	Portel	1505809
5394	2585	Porto de Moz	1505908
5395	2585	Prainha	1506005
5396	2585	Primavera	1506104
5397	2585	Quatipuru	1506112
5398	2585	Redencao	1506138
5399	2585	Rio Maria	1506161
5400	2585	Rondon do Para	1506187
5401	2585	Ruropolis	1506195
5402	2585	Salinopolis	1506203
5403	2585	Salvaterra	1506302
5404	2585	Santa Barbara do Para	1506351
5405	2585	Santa Cruz do Arari	1506401
5406	2585	Santa Isabel do Para	1506500
5407	2585	Santa Luzia do Para	1506559
5408	2585	Santa Maria das Barreiras	1506583
5409	2585	Santa Maria do Para	1506609
5410	2585	Santana do Araguaia	1506708
5411	2585	Santarem	1506807
5412	2585	Santarem Novo	1506906
5413	2585	Santo Antonio do Taua	1507003
5414	2585	Sao Caetano de Odivelas	1507102
5415	2585	Sao Domingos do Araguaia	1507151
5416	2585	Sao Domingos do Capim	1507201
5417	2585	Sao Felix do Xingu	1507300
5418	2585	Sao Francisco do Para	1507409
5419	2585	Sao Geraldo do Araguaia	1507458
5420	2585	Sao Joao da Ponta	1507466
5421	2585	Sao Joao de Pirabas	1507474
5422	2585	Sao Joao do Araguaia	1507508
5423	2585	Sao Miguel do Guama	1507607
5424	2585	Sao Sebastiao da Boa Vista	1507706
5425	2585	Sapucaia	1507755
5426	2585	Senador Jose Porfirio	1507805
5427	2585	Soure	1507904
5428	2585	Tailandia	1507953
5429	2585	Terra Alta	1507961
5430	2585	Terra Santa	1507979
5431	2585	Tome-Acu	1508001
5432	2585	Tracuateua	1508035
5433	2585	Trairao	1508050
5434	2585	Tucuma	1508084
5435	2585	Tucurui	1508100
5436	2585	Ulianopolis	1508126
5437	2585	Uruara	1508159
5438	2585	Vigia	1508209
5439	2585	Viseu	1508308
5440	2585	Vitoria do Xingu	1508357
5441	2585	Xinguara	1508407
5442	2575	Serra do Navio	1600055
5443	2575	Amapa	1600105
5444	2575	Pedra Branca do Amapari	1600154
5445	2575	Calcoene	1600204
5446	2575	Cutias	1600212
5447	2575	Ferreira Gomes	1600238
5448	2575	Itaubal	1600253
5449	2575	Laranjal do Jari	1600279
5450	2575	Macapa	1600303
5451	2575	Mazagao	1600402
5452	2575	Oiapoque	1600501
5453	2575	Porto Grande	1600535
5454	2575	Pracuuba	1600550
5455	2575	Santana	1600600
5456	2575	Tartarugalzinho	1600709
5457	2575	Vitoria do Jari	1600808
5458	2598	Abreulandia	1700251
5459	2598	Aguiarnopolis	1700301
5460	2598	Alianca do Tocantins	1700350
5461	2598	Almas	1700400
5462	2598	Alvorada	1700707
5463	2598	Ananas	1701002
5464	2598	Angico	1701051
5465	2598	Aparecida do Rio Negro	1701101
5466	2598	Aragominas	1701309
5467	2598	Araguacema	1701903
5468	2598	Araguacu	1702000
5469	2598	Araguaina	1702109
5470	2598	Araguana	1702158
5471	2598	Araguatins	1702208
5472	2598	Arapoema	1702307
5473	2598	Arraias	1702406
5474	2598	Augustinopolis	1702554
5475	2598	Aurora do Tocantins	1702703
5476	2598	Axixa do Tocantins	1702901
5477	2598	Babaculandia	1703008
5478	2598	Bandeirantes do Tocantins	1703057
5479	2598	Barra do Ouro	1703073
5480	2598	Barrolandia	1703107
5481	2598	Bernardo Sayao	1703206
5482	2598	Bom Jesus do Tocantins	1703305
5483	2598	Brasilandia do Tocantins	1703602
5484	2598	Brejinho de Nazare	1703701
5485	2598	Buriti do Tocantins	1703800
5486	2598	Cachoeirinha	1703826
5487	2598	Campos Lindos	1703842
5488	2598	Cariri do Tocantins	1703867
5489	2598	Carmolandia	1703883
5490	2598	Carrasco Bonito	1703891
5491	2598	Caseara	1703909
5492	2598	Centenario	1704105
5493	2598	Chapada de Areia	1704600
5494	2598	Chapada da Natividade	1705102
5495	2598	Colinas do Tocantins	1705508
5496	2598	Combinado	1705557
5497	2598	Conceicao do Tocantins	1705607
5498	2598	Couto de Magalhaes	1706001
5499	2598	Cristalandia	1706100
5500	2598	Crixas do Tocantins	1706258
5501	2598	Darcinopolis	1706506
5502	2598	Dianopolis	1707009
5503	2598	Divinopolis do Tocantins	1707108
5504	2598	Dois Irmaos do Tocantins	1707207
5505	2598	Duere	1707306
5506	2598	Esperantina	1707405
5507	2598	Fatima	1707553
5508	2598	Figueiropolis	1707652
5509	2598	Filadelfia	1707702
5510	2598	Formoso do Araguaia	1708205
5511	2598	Fortaleza do Tabocao	1708254
5512	2598	Goianorte	1708304
5513	2598	Goiatins	1709005
5514	2598	Guarai	1709302
5515	2598	Gurupi	1709500
5516	2598	Ipueiras	1709807
5517	2598	Itacaja	1710508
5518	2598	Itaguatins	1710706
5519	2598	Itapiratins	1710904
5520	2598	Itapora do Tocantins	1711100
5521	2598	Jau do Tocantins	1711506
5522	2598	Juarina	1711803
5523	2598	Lagoa da Confusao	1711902
5524	2598	Lagoa do Tocantins	1711951
5525	2598	Lajeado	1712009
5526	2598	Lavandeira	1712157
5527	2598	Lizarda	1712405
5528	2598	Luzinopolis	1712454
5529	2598	Marianopolis do Tocantins	1712504
5530	2598	Mateiros	1712702
5531	2598	Maurilandia do Tocantins	1712801
5532	2598	Miracema do Tocantins	1713205
5533	2598	Miranorte	1713304
5534	2598	Monte do Carmo	1713601
5535	2598	Monte Santo do Tocantins	1713700
5536	2598	Mosquito	1713809
5537	2598	Muricilandia	1713957
5538	2598	Natividade	1714203
5539	2598	Nazare	1714302
5540	2598	Nova Olinda	1714880
5541	2598	Nova Rosalandia	1715002
5542	2598	Novo Acordo	1715101
5543	2598	Novo Alegre	1715150
5544	2598	Novo Jardim	1715259
5545	2598	Oliveira de Fatima	1715507
5546	2598	Palmeirante	1715705
5547	2598	Palmeiropolis	1715754
5548	2598	Paraiso do Tocantins	1716109
5549	2598	Parana	1716208
5550	2598	Pau D'Arco	1716307
5551	2598	Pedro Afonso	1716505
5552	2598	Peixe	1716604
5553	2598	Pequizeiro	1716653
5554	2598	Colmeia	1716703
5555	2598	Pindorama do Tocantins	1717008
5556	2598	Piraque	1717206
5557	2598	Pium	1717503
5558	2598	Ponte Alta do Bom Jesus	1717800
5559	2598	Ponte Alta do Tocantins	1717909
5560	2598	Porto Alegre do Tocantins	1718006
5561	2598	Porto Nacional	1718204
5562	2598	Praia Norte	1718303
5563	2598	Presidente Kennedy	1718402
5564	2598	Pugmil	1718451
5565	2598	Recursolandia	1718501
5566	2598	Riachinho	1718550
5567	2598	Rio da Conceicao	1718659
5568	2598	Rio dos Bois	1718709
5569	2598	Rio Sono	1718758
5570	2598	Sampaio	1718808
5571	2598	Sandolandia	1718840
5572	2598	Santa Fe do Araguaia	1718865
5573	2598	Santa Maria do Tocantins	1718881
5574	2598	Santa Rita do Tocantins	1718899
5575	2598	Santa Rosa do Tocantins	1718907
5576	2598	Santa Tereza do Tocantins	1719004
5577	2598	Santa Terezinha do Tocantins	1720002
5578	2598	Sao Bento do Tocantins	1720101
5579	2598	Sao Felix do Tocantins	1720150
5580	2598	Sao Miguel do Tocantins	1720200
5581	2598	Sao Salvador do Tocantins	1720259
5582	2598	Sao Sebastiao do Tocantins	1720309
5583	2598	Sao Valerio da Natividade	1720499
5584	2598	Silvanopolis	1720655
5585	2598	Sitio Novo do Tocantins	1720804
5586	2598	Sucupira	1720853
5587	2598	Taguatinga	1720903
5588	2598	Taipas do Tocantins	1720937
5589	2598	Talisma	1720978
5590	2598	Palmas	1721000
5591	2598	Tocantinia	1721109
5592	2598	Tocantinopolis	1721208
5593	2598	Tupirama	1721257
5594	2598	Tupiratins	1721307
5595	2598	Wanderlandia	1722081
5596	2598	Xambioa	1722107
5597	2581	Acailandia	2100055
5598	2581	Afonso Cunha	2100105
5599	2581	Agua Doce do Maranhao	2100154
5600	2581	Alcantara	2100204
5601	2581	Aldeias Altas	2100303
5602	2581	Altamira do Maranhao	2100402
5603	2581	Alto Alegre do Maranhao	2100436
5604	2581	Alto Alegre do Pindare	2100477
5605	2581	Alto Parnaiba	2100501
5606	2581	Amapa do Maranhao	2100550
5607	2581	Amarante do Maranhao	2100600
5608	2581	Anajatuba	2100709
5609	2581	Anapurus	2100808
5610	2581	Apicum-Acu	2100832
5611	2581	Araguana	2100873
5612	2581	Araioses	2100907
5613	2581	Arame	2100956
5614	2581	Arari	2101004
5615	2581	Axixa	2101103
5616	2581	Bacabal	2101202
5617	2581	Bacabeira	2101251
5618	2581	Bacuri	2101301
5619	2581	Bacurituba	2101350
5620	2581	Balsas	2101400
5621	2581	Barao de Grajau	2101509
5622	2581	Barra do Corda	2101608
5623	2581	Barreirinhas	2101707
5624	2581	Belagua	2101731
5625	2581	Bela Vista do Maranhao	2101772
5626	2581	Benedito Leite	2101806
5627	2581	Bequimao	2101905
5628	2581	Bernardo do Mearim	2101939
5629	2581	Boa Vista do Gurupi	2101970
5630	2581	Bom Jardim	2102002
5631	2581	Bom Jesus das Selvas	2102036
5632	2581	Bom Lugar	2102077
5633	2581	Brejo	2102101
5634	2581	Brejo de Areia	2102150
5635	2581	Buriti	2102200
5636	2581	Buriti Bravo	2102309
5637	2581	Buriticupu	2102325
5638	2581	Buritirana	2102358
5639	2581	Cachoeira Grande	2102374
5640	2581	Cajapio	2102408
5641	2581	Cajari	2102507
5642	2581	Campestre do Maranhao	2102556
5643	2581	Candido Mendes	2102606
5644	2581	Cantanhede	2102705
5645	2581	Capinzal do Norte	2102754
5646	2581	Carolina	2102804
5647	2581	Carutapera	2102903
5648	2581	Caxias	2103000
5649	2581	Cedral	2103109
5650	2581	Central do Maranhao	2103125
5651	2581	Centro do Guilherme	2103158
5652	2581	Centro Novo do Maranhao	2103174
5653	2581	Chapadinha	2103208
5654	2581	Cidelandia	2103257
5655	2581	Codo	2103307
5656	2581	Coelho Neto	2103406
5657	2581	Colinas	2103505
5658	2581	Conceicao do Lago-Acu	2103554
5659	2581	Coroata	2103604
5660	2581	Cururupu	2103703
5661	2581	Davinopolis	2103752
5662	2581	Dom Pedro	2103802
5663	2581	Duque Bacelar	2103901
5664	2581	Esperantinopolis	2104008
5665	2581	Estreito	2104057
5666	2581	Feira Nova do Maranhao	2104073
5667	2581	Fernando Falcao	2104081
5668	2581	Formosa da Serra Negra	2104099
5669	2581	Fortaleza dos Nogueiras	2104107
5670	2581	Fortuna	2104206
5671	2581	Godofredo Viana	2104305
5672	2581	Goncalves Dias	2104404
5673	2581	Governador Archer	2104503
5674	2581	Governador Edison Lobao	2104552
5675	2581	Governador Eugenio Barros	2104602
5676	2581	Governador Luiz Rocha	2104628
5677	2581	Governador Newton Bello	2104651
5678	2581	Governador Nunes Freire	2104677
5679	2581	Graca Aranha	2104701
5680	2581	Grajau	2104800
5681	2581	Guimaraes	2104909
5682	2581	Humberto de Campos	2105005
5683	2581	Icatu	2105104
5684	2581	Igarape do Meio	2105153
5685	2581	Igarape Grande	2105203
5686	2581	Imperatriz	2105302
5687	2581	Itaipava do Grajau	2105351
5688	2581	Itapecuru Mirim	2105401
5689	2581	Itinga do Maranhao	2105427
5690	2581	Jatoba	2105450
5691	2581	Jenipapo dos Vieiras	2105476
5692	2581	Joao Lisboa	2105500
5693	2581	Joselandia	2105609
5694	2581	Junco do Maranhao	2105658
5695	2581	Lago da Pedra	2105708
5696	2581	Lago do Junco	2105807
5697	2581	Lago Verde	2105906
5698	2581	Lagoa do Mato	2105922
5699	2581	Lagoa dos Rodrigues	2105948
5700	2581	Lagoa Grande do Maranhao	2105963
5701	2581	Lajeado Novo	2105989
5702	2581	Lima Campos	2106003
5703	2581	Loreto	2106102
5704	2581	Luis Domingues	2106201
5705	2581	Magalhaes de Almeida	2106300
5706	2581	Maracacume	2106326
5707	2581	Maraja do Sena	2106359
5708	2581	Maranhaozinho	2106375
5709	2581	Mata Roma	2106409
5710	2581	Matinha	2106508
5711	2581	Matoes	2106607
5712	2581	Matoes do Norte	2106631
5713	2581	Milagres do Maranhao	2106672
5714	2581	Mirador	2106706
5715	2581	Miranda do Norte	2106755
5716	2581	Mirinzal	2106805
5717	2581	Moncao	2106904
5718	2581	Montes Altos	2107001
5719	2581	Morros	2107100
5720	2581	Nina Rodrigues	2107209
5721	2581	Nova Colinas	2107258
5722	2581	Nova Iorque	2107308
5723	2581	Nova Olinda do Maranhao	2107357
5724	2581	Olho d'Agua das Cunhas	2107407
5725	2581	Olinda Nova do Maranhao	2107456
5726	2581	Paco do Lumiar	2107506
5727	2581	Palmeirandia	2107605
5728	2581	Paraibano	2107704
5729	2581	Parnarama	2107803
5730	2581	Passagem Franca	2107902
5731	2581	Pastos Bons	2108009
5732	2581	Paulino Neves	2108058
5733	2581	Paulo Ramos	2108108
5734	2581	Pedreiras	2108207
5735	2581	Pedro do Rosario	2108256
5736	2581	Penalva	2108306
5737	2581	Peri Mirim	2108405
5738	2581	Peritoro	2108454
5739	2581	Pindare Mirim	2108504
5740	2581	Pinheiro	2108603
5741	2581	Pio XII	2108702
5742	2581	Pirapemas	2108801
5743	2581	Pocao de Pedras	2108900
5744	2581	Porto Franco	2109007
5745	2581	Porto Rico do Maranhao	2109056
5746	2581	Presidente Dutra	2109106
5747	2581	Presidente Juscelino	2109205
5748	2581	Presidente Medici	2109239
5749	2581	Presidente Sarney	2109270
5750	2581	Presidente Vargas	2109304
5751	2581	Primeira Cruz	2109403
5752	2581	Raposa	2109452
5753	2581	Riachao	2109502
5754	2581	Ribamar Fiquene	2109551
5755	2581	Rosario	2109601
5756	2581	Sambaiba	2109700
5757	2581	Santa Filomena do Maranhao	2109759
5758	2581	Santa Helena	2109809
5759	2581	Santa Ines	2109908
5760	2581	Santa Luzia	2110005
5761	2581	Santa Luzia do Parua	2110039
5762	2581	Santa Quiteria do Maranhao	2110104
5763	2581	Santa Rita	2110203
5764	2581	Santana do Maranhao	2110237
5765	2581	Santo Amaro do Maranhao	2110278
5766	2581	Santo Antonio dos Lopes	2110302
5767	2581	Sao Benedito do Rio Preto	2110401
5768	2581	Sao Bento	2110500
5769	2581	Sao Bernardo	2110609
5770	2581	Sao Domingos do Azeitao	2110658
5771	2581	Sao Domingos do Maranhao	2110708
5772	2581	Sao Felix de Balsas	2110807
5773	2581	Sao Francisco do Brejao	2110856
5774	2581	Sao Francisco do Maranhao	2110906
5775	2581	Sao Joao Batista	2111003
5776	2581	Sao Joao do Caru	2111029
5777	2581	Sao Joao do Paraiso	2111052
5778	2581	Sao Joao do Soter	2111078
5779	2581	Sao Joao dos Patos	2111102
5780	2581	Sao Jose de Ribamar	2111201
5781	2581	Sao Jose dos Basilios	2111250
5782	2581	Sao Luis	2111300
5783	2581	Sao Luis Gonzaga do Maranhao	2111409
5784	2581	Sao Mateus do Maranhao	2111508
5785	2581	Sao Pedro da Agua Branca	2111532
5786	2581	Sao Pedro dos Crentes	2111573
5787	2581	Sao Raimundo das Mangabeiras	2111607
5788	2581	Sao Raimundo do Doca Bezerra	2111631
5789	2581	Sao Roberto	2111672
5790	2581	Sao Vicente Ferrer	2111706
5791	2581	Satubinha	2111722
5792	2581	Senador Alexandre Costa	2111748
5793	2581	Senador La Rocque	2111763
5794	2581	Serrano do Maranhao	2111789
5795	2581	Sitio Novo	2111805
5796	2581	Sucupira do Norte	2111904
5797	2581	Sucupira do Riachao	2111953
5798	2581	Tasso Fragoso	2112001
5799	2581	Timbiras	2112100
5800	2581	Timon	2112209
5801	2581	Trizidela do Vale	2112233
5802	2581	Tufilandia	2112274
5803	2581	Tuntum	2112308
5804	2581	Turiacu	2112407
5805	2581	Turilandia	2112456
5806	2581	Tutoia	2112506
5807	2581	Urbano Santos	2112605
5808	2581	Vargem Grande	2112704
5809	2581	Viana	2112803
5810	2581	Vila Nova dos Martirios	2112852
5811	2581	Vitoria do Mearim	2112902
5812	2581	Vitorino Freire	2113009
5813	2581	Ze Doca	2114007
5814	2589	Acaua	2200053
5815	2589	Agricolandia	2200103
5816	2589	Agua Branca	2200202
5817	2589	Alagoinha do Piaui	2200251
5818	2589	Alegrete do Piaui	2200277
5819	2589	Alto Longa	2200301
5820	2589	Altos	2200400
5821	2589	Alvorada do Gurgueia	2200459
5822	2589	Amarante	2200509
5823	2589	Angical do Piaui	2200608
5824	2589	Anisio de Abreu	2200707
5825	2589	Antonio Almeida	2200806
5826	2589	Aroazes	2200905
5827	2589	AROEIRA DO ITAIM	2200954
5828	2589	Arraial	2201002
5829	2589	Assuncao do Piaui	2201051
5830	2589	Avelino Lopes	2201101
5831	2589	Baixa Grande do Ribeiro	2201150
5832	2589	Barra D'Alcantara	2201176
5833	2589	Barras	2201200
5834	2589	Barreiras do Piaui	2201309
5835	2589	Barro Duro	2201408
5836	2589	Batalha	2201507
5837	2589	Bela Vista do Piaui	2201556
5838	2589	Belem do Piaui	2201572
5839	2589	Beneditinos	2201606
5840	2589	Bertolinia	2201705
5841	2589	Betania do Piaui	2201739
5842	2589	Boa Hora	2201770
5843	2589	Bocaina	2201804
5844	2589	Bom Jesus	2201903
5845	2589	Bom Principio do Piaui	2201919
5846	2589	Bonfim do Piaui	2201929
5847	2589	Boqueirao do Piaui	2201945
5848	2589	Brasileira	2201960
5849	2589	Brejo do Piaui	2201988
5850	2589	Buriti dos Lopes	2202000
5851	2589	Buriti dos Montes	2202026
5852	2589	Cabeceiras do Piaui	2202059
5853	2589	Cajazeiras do Piaui	2202075
5854	2589	Cajueiro da Praia	2202083
5855	2589	Caldeirao Grande do Piaui	2202091
5856	2589	Campinas do Piaui	2202109
5857	2589	Campo Alegre do Fidalgo	2202117
5858	2589	Campo Grande do Piaui	2202133
5859	2589	Campo Largo do Piaui	2202174
5860	2589	Campo Maior	2202208
5861	2589	Canavieira	2202251
5862	2589	Canto do Buriti	2202307
5863	2589	Capitao de Campos	2202406
5864	2589	Capitao Gervasio Oliveira	2202455
5865	2589	Caracol	2202505
5866	2589	Caraubas do Piaui	2202539
5867	2589	Caridade do Piaui	2202554
5868	2589	Castelo do Piaui	2202604
5869	2589	Caxingo	2202653
5870	2589	Cocal	2202703
5871	2589	Cocal de Telha	2202711
5872	2589	Cocal dos Alves	2202729
5873	2589	Coivaras	2202737
5874	2589	Colonia do Gurgueia	2202752
5875	2589	Colonia do Piaui	2202778
5876	2589	Conceicao do Caninde	2202802
5877	2589	Coronel Jose Dias	2202851
5878	2589	Corrente	2202901
5879	2589	Cristalandia do Piaui	2203008
5880	2589	Cristino Castro	2203107
5881	2589	Curimata	2203206
5882	2589	Currais	2203230
5883	2589	Curralinhos	2203255
5884	2589	Curral Novo do Piaui	2203271
5885	2589	Demerval Lobao	2203305
5886	2589	Dirceu Arcoverde	2203354
5887	2589	Dom Expedito Lopes	2203404
5888	2589	Domingos Mourao	2203420
5889	2589	Dom Inocencio	2203453
5890	2589	Elesbao Veloso	2203503
5891	2589	Eliseu Martins	2203602
5892	2589	Esperantina	2203701
5893	2589	Fartura do Piaui	2203750
5894	2589	Flores do Piaui	2203800
5895	2589	Floresta do Piaui	2203859
5896	2589	Floriano	2203909
5897	2589	Francinopolis	2204006
5898	2589	Francisco Ayres	2204105
5899	2589	Francisco Macedo	2204154
5900	2589	Francisco Santos	2204204
5901	2589	Fronteiras	2204303
5902	2589	Geminiano	2204352
5903	2589	Gilbues	2204402
5904	2589	Guadalupe	2204501
5905	2589	Guaribas	2204550
5906	2589	Hugo Napoleao	2204600
5907	2589	Ilha Grande	2204659
5908	2589	Inhuma	2204709
5909	2589	Ipiranga do Piaui	2204808
5910	2589	Isaias Coelho	2204907
5911	2589	Itainopolis	2205003
5912	2589	Itaueira	2205102
5913	2589	Jacobina do Piaui	2205151
5914	2589	Jaicos	2205201
5915	2589	Jardim do Mulato	2205250
5916	2589	Jatoba do Piaui	2205276
5917	2589	Jerumenha	2205300
5918	2589	Joao Costa	2205359
5919	2589	Joaquim Pires	2205409
5920	2589	Joca Marques	2205458
5921	2589	Jose de Freitas	2205508
5922	2589	Juazeiro do Piaui	2205516
5923	2589	Julio Borges	2205524
5924	2589	Jurema	2205532
5925	2589	Lagoinha do Piaui	2205540
5926	2589	Lagoa Alegre	2205557
5927	2589	Lagoa do Barro do Piaui	2205565
5928	2589	Lagoa de Sao Francisco	2205573
5929	2589	Lagoa do Piaui	2205581
5930	2589	Lagoa do Sitio	2205599
5931	2589	Landri Sales	2205607
5932	2589	Luis Correia	2205706
5933	2589	Luzilandia	2205805
5934	2589	Madeiro	2205854
5935	2589	Manoel Emidio	2205904
5936	2589	Marcolandia	2205953
5937	2589	Marcos Parente	2206001
5938	2589	Massape do Piaui	2206050
5939	2589	Matias Olimpio	2206100
5940	2589	Miguel Alves	2206209
5941	2589	Miguel Leao	2206308
5942	2589	Milton Brandao	2206357
5943	2589	Monsenhor Gil	2206407
5944	2589	Monsenhor Hipolito	2206506
5945	2589	Monte Alegre do Piaui	2206605
5946	2589	Morro Cabeca no Tempo	2206654
5947	2589	Morro do Chapeu do Piaui	2206670
5948	2589	Murici dos Portelas	2206696
5949	2589	Nazare do Piaui	2206704
5950	2589	Nossa Senhora de Nazare	2206753
5951	2589	Nossa Senhora dos Remedios	2206803
5952	2589	Novo Oriente do Piaui	2206902
5953	2589	Novo Santo Antonio	2206951
5954	2589	Oeiras	2207009
5955	2589	Olho D'Agua do Piaui	2207108
5956	2589	Padre Marcos	2207207
5957	2589	Paes Landim	2207306
5958	2589	Pajeu do Piaui	2207355
5959	2589	Palmeira do Piaui	2207405
5960	2589	Palmeirais	2207504
5961	2589	Paqueta	2207553
5962	2589	Parnagua	2207603
5963	2589	Parnaiba	2207702
5964	2589	Passagem Franca do Piaui	2207751
5965	2589	Patos do Piaui	2207777
5966	2589	PAU DARCO DO PIAUI	2207793
5967	2589	Paulistana	2207801
5968	2589	Pavussu	2207850
5969	2589	Pedro II	2207900
5970	2589	Pedro Laurentino	2207934
5971	2589	Petronio Portela	2207959
5972	2589	Picos	2208007
5973	2589	Pimenteiras	2208106
5974	2589	Pio IX	2208205
5975	2589	Piracuruca	2208304
5976	2589	Piripiri	2208403
5977	2589	Porto	2208502
5978	2589	Porto Alegre do Piaui	2208551
5979	2589	Prata do Piaui	2208601
5980	2589	Queimada Nova	2208650
5981	2589	Redencao do Gurgueia	2208700
5982	2589	Regeneracao	2208809
5983	2589	Riacho Frio	2208858
5984	2589	Ribeira do Piaui	2208874
5985	2589	Ribeiro Goncalves	2208908
5986	2589	Rio Grande do Piaui	2209005
5987	2589	Santa Cruz do Piaui	2209104
5988	2589	Santa Cruz dos Milagres	2209153
5989	2589	Santa Filomena	2209203
5990	2589	Santa Luz	2209302
5991	2589	Santana do Piaui	2209351
5992	2589	Santa Rosa do Piaui	2209377
5993	2589	Santo Antonio de Lisboa	2209401
5994	2589	Santo Antonio dos Milagres	2209450
5995	2589	Santo Inacio do Piaui	2209500
5996	2589	Sao Braz do Piaui	2209559
5997	2589	Sao Felix do Piaui	2209609
5998	2589	Sao Francisco de Assis do Piau	2209658
5999	2589	Sao Francisco do Piaui	2209708
6000	2589	Sao Goncalo do Gurgueia	2209757
6001	2589	Sao Goncalo do Piaui	2209807
6002	2589	Sao Joao da Canabrava	2209856
6003	2589	Sao Joao da Fronteira	2209872
6004	2589	Sao Joao da Serra	2209906
6005	2589	Sao Joao da Varjota	2209955
6006	2589	Sao Joao do Arraial	2209971
6007	2589	Sao Joao do Piaui	2210003
6008	2589	Sao Jose do Divino	2210052
6009	2589	Sao Jose do Peixe	2210102
6010	2589	Sao Jose do Piaui	2210201
6011	2589	Sao Juliao	2210300
6012	2589	Sao Lourenco do Piaui	2210359
6013	2589	Sao Luis do Piaui	2210375
6014	2589	Sao Miguel da Baixa Grande	2210383
6015	2589	Sao Miguel do Fidalgo	2210391
6016	2589	Sao Miguel do Tapuio	2210409
6017	2589	Sao Pedro do Piaui	2210508
6018	2589	Sao Raimundo Nonato	2210607
6019	2589	Sebastiao Barros	2210623
6020	2589	Sebastiao Leal	2210631
6021	2589	Sigefredo Pacheco	2210656
6022	2589	Simoes	2210706
6023	2589	Simplicio Mendes	2210805
6024	2589	Socorro do Piaui	2210904
6025	2589	Sussuapara	2210938
6026	2589	Tamboril do Piaui	2210953
6027	2589	Tanque do Piaui	2210979
6028	2589	Teresina	2211001
6029	2589	Uniao	2211100
6030	2589	Urucui	2211209
6031	2589	Valenca do Piaui	2211308
6032	2589	Varzea Branca	2211357
6033	2589	Varzea Grande	2211407
6034	2589	Vera Mendes	2211506
6035	2589	Vila Nova do Piaui	2211605
6036	2589	Wall Ferraz	2211704
6037	2577	Abaiara	2300101
6038	2577	Acarape	2300150
6039	2577	Acarau	2300200
6040	2577	Acopiara	2300309
6041	2577	Aiuaba	2300408
6042	2577	Alcantaras	2300507
6043	2577	Altaneira	2300606
6044	2577	Alto Santo	2300705
6045	2577	Amontada	2300754
6046	2577	Antonina do Norte	2300804
6047	2577	Apuiares	2300903
6048	2577	Aquiraz	2301000
6049	2577	Aracati	2301109
6050	2577	Aracoiaba	2301208
6051	2577	Ararenda	2301257
6052	2577	Araripe	2301307
6053	2577	Aratuba	2301406
6054	2577	Arneiroz	2301505
6055	2577	Assare	2301604
6056	2577	Aurora	2301703
6057	2577	Baixio	2301802
6058	2577	Banabuiu	2301851
6059	2577	Barbalha	2301901
6060	2577	Barreira	2301950
6061	2577	Barro	2302008
6062	2577	Barroquinha	2302057
6063	2577	Baturite	2302107
6064	2577	Beberibe	2302206
6065	2577	Bela Cruz	2302305
6066	2577	Boa Viagem	2302404
6067	2577	Brejo Santo	2302503
6068	2577	Camocim	2302602
6069	2577	Campos Sales	2302701
6070	2577	Caninde	2302800
6071	2577	Capistrano	2302909
6072	2577	Caridade	2303006
6073	2577	Carire	2303105
6074	2577	Caririacu	2303204
6075	2577	Carius	2303303
6076	2577	Carnaubal	2303402
6077	2577	Cascavel	2303501
6078	2577	Catarina	2303600
6079	2577	Catunda	2303659
6080	2577	Caucaia	2303709
6081	2577	Cedro	2303808
6082	2577	Chaval	2303907
6083	2577	Choro	2303931
6084	2577	Chorozinho	2303956
6085	2577	Coreau	2304004
6086	2577	Crateus	2304103
6087	2577	Crato	2304202
6088	2577	Croata	2304236
6089	2577	Cruz	2304251
6090	2577	Deputado Irapuan Pinheiro	2304269
6091	2577	Erere	2304277
6092	2577	Eusebio	2304285
6093	2577	Farias Brito	2304301
6094	2577	Forquilha	2304350
6095	2577	Fortaleza	2304400
6096	2577	Fortim	2304459
6097	2577	Frecheirinha	2304509
6098	2577	General Sampaio	2304608
6099	2577	Graca	2304657
6100	2577	Granja	2304707
6101	2577	Granjeiro	2304806
6102	2577	Groairas	2304905
6103	2577	Guaiuba	2304954
6104	2577	Guaraciaba do Norte	2305001
6105	2577	Guaramiranga	2305100
6106	2577	Hidrolandia	2305209
6107	2577	Horizonte	2305233
6108	2577	Ibaretama	2305266
6109	2577	Ibiapina	2305308
6110	2577	Ibicuitinga	2305332
6111	2577	Icapui	2305357
6112	2577	Ico	2305407
6113	2577	Iguatu	2305506
6114	2577	Independencia	2305605
6115	2577	Ipaporanga	2305654
6116	2577	Ipaumirim	2305704
6117	2577	Ipu	2305803
6118	2577	Ipueiras	2305902
6119	2577	Iracema	2306009
6120	2577	Iraucuba	2306108
6121	2577	Itaicaba	2306207
6122	2577	Itaitinga	2306256
6123	2577	Itapage	2306306
6124	2577	Itapipoca	2306405
6125	2577	Itapiuna	2306504
6126	2577	Itarema	2306553
6127	2577	Itatira	2306603
6128	2577	Jaguaretama	2306702
6129	2577	Jaguaribara	2306801
6130	2577	Jaguaribe	2306900
6131	2577	Jaguaruana	2307007
6132	2577	Jardim	2307106
6133	2577	Jati	2307205
6134	2577	Jijoca de Jericoacoara	2307254
6135	2577	Juazeiro do Norte	2307304
6136	2577	Jucas	2307403
6137	2577	Lavras da Mangabeira	2307502
6138	2577	Limoeiro do Norte	2307601
6139	2577	Madalena	2307635
6140	2577	Maracanau	2307650
6141	2577	Maranguape	2307700
6142	2577	Marco	2307809
6143	2577	Martinopole	2307908
6144	2577	Massape	2308005
6145	2577	Mauriti	2308104
6146	2577	Meruoca	2308203
6147	2577	Milagres	2308302
6148	2577	Milha	2308351
6149	2577	Miraima	2308377
6150	2577	Missao Velha	2308401
6151	2577	Mombaca	2308500
6152	2577	Monsenhor Tabosa	2308609
6153	2577	Morada Nova	2308708
6154	2577	Moraujo	2308807
6155	2577	Morrinhos	2308906
6156	2577	Mucambo	2309003
6157	2577	Mulungu	2309102
6158	2577	Nova Olinda	2309201
6159	2577	Nova Russas	2309300
6160	2577	Novo Oriente	2309409
6161	2577	Ocara	2309458
6162	2577	Oros	2309508
6163	2577	Pacajus	2309607
6164	2577	Pacatuba	2309706
6165	2577	Pacoti	2309805
6166	2577	Pacuja	2309904
6167	2577	Palhano	2310001
6168	2577	Palmacia	2310100
6169	2577	Paracuru	2310209
6170	2577	Paraipaba	2310258
6171	2577	Parambu	2310308
6172	2577	Paramoti	2310407
6173	2577	Pedra Branca	2310506
6174	2577	Penaforte	2310605
6175	2577	Pentecoste	2310704
6176	2577	Pereiro	2310803
6177	2577	Pindoretama	2310852
6178	2577	Piquet Carneiro	2310902
6179	2577	Pires Ferreira	2310951
6180	2577	Poranga	2311009
6181	2577	Porteiras	2311108
6182	2577	Potengi	2311207
6183	2577	Potiretama	2311231
6184	2577	Quiterianopolis	2311264
6185	2577	Quixada	2311306
6186	2577	Quixelo	2311355
6187	2577	Quixeramobim	2311405
6188	2577	Quixere	2311504
6189	2577	Redencao	2311603
6190	2577	Reriutaba	2311702
6191	2577	Russas	2311801
6192	2577	Saboeiro	2311900
6193	2577	Salitre	2311959
6194	2577	Santana do Acarau	2312007
6195	2577	Santana do Cariri	2312106
6196	2577	Santa Quiteria	2312205
6197	2577	Sao Benedito	2312304
6198	2577	Sao Goncalo do Amarante	2312403
6199	2577	Sao Joao do Jaguaribe	2312502
6200	2577	Sao Luis do Curu	2312601
6201	2577	Senador Pompeu	2312700
6202	2577	Senador Sa	2312809
6203	2577	Sobral	2312908
6204	2577	Solonopole	2313005
6205	2577	Tabuleiro do Norte	2313104
6206	2577	Tamboril	2313203
6207	2577	Tarrafas	2313252
6208	2577	Taua	2313302
6209	2577	Tejucuoca	2313351
6210	2577	Tiangua	2313401
6211	2577	Trairi	2313500
6212	2577	Tururu	2313559
6213	2577	Ubajara	2313609
6214	2577	Umari	2313708
6215	2577	Umirim	2313757
6216	2577	Uruburetama	2313807
6217	2577	Uruoca	2313906
6218	2577	Varjota	2313955
6219	2577	Varzea Alegre	2314003
6220	2577	Vicosa do Ceara	2314102
6221	2591	Acari	2400109
6222	2591	Acu	2400208
6223	2591	Afonso Bezerra	2400307
6224	2591	Agua Nova	2400406
6225	2591	Alexandria	2400505
6226	2591	Almino Afonso	2400604
6227	2591	Alto do Rodrigues	2400703
6228	2591	Angicos	2400802
6229	2591	Antonio Martins	2400901
6230	2591	Apodi	2401008
6231	2591	Areia Branca	2401107
6232	2591	Ares	2401206
6233	2591	Augusto Severo	2401305
6234	2591	Baia Formosa	2401404
6235	2591	Barauna	2401453
6236	2591	Barcelona	2401503
6237	2591	Bento Fernandes	2401602
6238	2591	Bodo	2401651
6239	2591	Bom Jesus	2401701
6240	2591	Brejinho	2401800
6241	2591	Caicara do Norte	2401859
6242	2591	Caicara do Rio do Vento	2401909
6243	2591	Caico	2402006
6244	2591	Campo Redondo	2402105
6245	2591	Canguaretama	2402204
6246	2591	Caraubas	2402303
6247	2591	Carnauba dos Dantas	2402402
6248	2591	Carnaubais	2402501
6249	2591	Ceara-Mirim	2402600
6250	2591	Cerro Cora	2402709
6251	2591	Coronel Ezequiel	2402808
6252	2591	Coronel Joao Pessoa	2402907
6253	2591	Cruzeta	2403004
6254	2591	Currais Novos	2403103
6255	2591	Doutor Severiano	2403202
6256	2591	Parnamirim	2403251
6257	2591	Encanto	2403301
6258	2591	Equador	2403400
6259	2591	Espirito Santo	2403509
6260	2591	Extremoz	2403608
6261	2591	Felipe Guerra	2403707
6262	2591	Fernando Pedroza	2403756
6263	2591	Florania	2403806
6264	2591	Francisco Dantas	2403905
6265	2591	Frutuoso Gomes	2404002
6266	2591	Galinhos	2404101
6267	2591	Goianinha	2404200
6268	2591	Governador Dix-Sept Rosado	2404309
6269	2591	Grossos	2404408
6270	2591	Guamare	2404507
6271	2591	Ielmo Marinho	2404606
6272	2591	Ipanguacu	2404705
6273	2591	Ipueira	2404804
6274	2591	Itaja	2404853
6275	2591	Itau	2404903
6276	2591	Jacana	2405009
6277	2591	Jandaira	2405108
6278	2591	Janduis	2405207
6279	2591	Januario Cicco	2405306
6280	2591	Japi	2405405
6281	2591	Jardim de Angicos	2405504
6282	2591	Jardim de Piranhas	2405603
6283	2591	Jardim do Serido	2405702
6284	2591	Joao Camara	2405801
6285	2591	Joao Dias	2405900
6286	2591	Jose da Penha	2406007
6287	2591	Jucurutu	2406106
6288	2591	JUNDIA	2406155
6289	2591	Lagoa d'Anta	2406205
6290	2591	Lagoa de Pedras	2406304
6291	2591	Lagoa de Velhos	2406403
6292	2591	Lagoa Nova	2406502
6293	2591	Lagoa Salgada	2406601
6294	2591	Lajes	2406700
6295	2591	Lajes Pintadas	2406809
6296	2591	Lucrecia	2406908
6297	2591	Luis Gomes	2407005
6298	2591	Macaiba	2407104
6299	2591	Macau	2407203
6300	2591	Major Sales	2407252
6301	2591	Marcelino Vieira	2407302
6302	2591	Martins	2407401
6303	2591	Maxaranguape	2407500
6304	2591	Messias Targino	2407609
6305	2591	Montanhas	2407708
6306	2591	Monte Alegre	2407807
6307	2591	Monte das Gameleiras	2407906
6308	2591	Mossoro	2408003
6309	2591	Natal	2408102
6310	2591	Nisia Floresta	2408201
6311	2591	Nova Cruz	2408300
6312	2591	Olho-d'Agua do Borges	2408409
6313	2591	Ouro Branco	2408508
6314	2591	Parana	2408607
6315	2591	Parau	2408706
6316	2591	Parazinho	2408805
6317	2591	Parelhas	2408904
6318	2591	Rio do Fogo	2408953
6319	2591	Passa e Fica	2409100
6320	2591	Passagem	2409209
6321	2591	Patu	2409308
6322	2591	Santa Maria	2409332
6323	2591	Pau dos Ferros	2409407
6324	2591	Pedra Grande	2409506
6325	2591	Pedra Preta	2409605
6326	2591	Pedro Avelino	2409704
6327	2591	Pedro Velho	2409803
6328	2591	Pendencias	2409902
6329	2591	Piloes	2410009
6330	2591	Poco Branco	2410108
6331	2591	Portalegre	2410207
6332	2591	Porto do Mangue	2410256
6333	2591	Presidente Juscelino	2410306
6334	2591	Pureza	2410405
6335	2591	Rafael Fernandes	2410504
6336	2591	Rafael Godeiro	2410603
6337	2591	Riacho da Cruz	2410702
6338	2591	Riacho de Santana	2410801
6339	2591	Riachuelo	2410900
6340	2591	Rodolfo Fernandes	2411007
6341	2591	Tibau	2411056
6342	2591	Ruy Barbosa	2411106
6343	2591	Santa Cruz	2411205
6344	2591	Santana do Matos	2411403
6345	2591	Santana do Serido	2411429
6346	2591	Santo Antonio	2411502
6347	2591	Sao Bento do Norte	2411601
6348	2591	Sao Bento do Trairi	2411700
6349	2591	Sao Fernando	2411809
6350	2591	Sao Francisco do Oeste	2411908
6351	2591	Sao Goncalo do Amarante	2412005
6352	2591	Sao Joao do Sabugi	2412104
6353	2591	Sao Jose de Mipibu	2412203
6354	2591	Sao Jose do Campestre	2412302
6355	2591	Sao Jose do Serido	2412401
6356	2591	Sao Miguel	2412500
6357	2591	Sao Miguel de Touros	2412559
6358	2591	Sao Paulo do Potengi	2412609
6359	2591	Sao Pedro	2412708
6360	2591	Sao Rafael	2412807
6361	2591	Sao Tome	2412906
6362	2591	Sao Vicente	2413003
6363	2591	Senador Eloi de Souza	2413102
6364	2591	Senador Georgino Avelino	2413201
6365	2591	Serra de Sao Bento	2413300
6366	2591	Serra do Mel	2413359
6367	2591	Serra Negra do Norte	2413409
6368	2591	Serrinha	2413508
6369	2591	Serrinha dos Pintos	2413557
6370	2591	Severiano Melo	2413607
6371	2591	Sitio Novo	2413706
6372	2591	Taboleiro Grande	2413805
6373	2591	Taipu	2413904
6374	2591	Tangara	2414001
6375	2591	Tenente Ananias	2414100
6376	2591	Tenente Laurentino Cruz	2414159
6377	2591	Tibau do Sul	2414209
6378	2591	Timbauba dos Batistas	2414308
6379	2591	Touros	2414407
6380	2591	Triunfo Potiguar	2414456
6381	2591	Umarizal	2414506
6382	2591	Upanema	2414605
6383	2591	Varzea	2414704
6384	2591	Venha-Ver	2414753
6385	2591	Vera Cruz	2414803
6386	2591	Vicosa	2414902
6387	2591	Vila Flor	2415008
6388	2586	Agua Branca	2500106
6389	2586	Aguiar	2500205
6390	2586	Alagoa Grande	2500304
6391	2586	Alagoa Nova	2500403
6392	2586	Alagoinha	2500502
6393	2586	Alcantil	2500536
6394	2586	Algodao de Jandaira	2500577
6395	2586	Alhandra	2500601
6396	2586	Sao Joao do Rio do Peixe	2500700
6397	2586	Amparo	2500734
6398	2586	Aparecida	2500775
6399	2586	Aracagi	2500809
6400	2586	Arara	2500908
6401	2586	Araruna	2501005
6402	2586	Areia	2501104
6403	2586	Areia de Baraunas	2501153
6404	2586	Areial	2501203
6405	2586	Aroeiras	2501302
6406	2586	Assuncao	2501351
6407	2586	Baia da Traicao	2501401
6408	2586	Bananeiras	2501500
6409	2586	Barauna	2501534
6410	2586	Barra de Santana	2501575
6411	2586	Barra de Santa Rosa	2501609
6412	2586	Barra de Sao Miguel	2501708
6413	2586	Bayeux	2501807
6414	2586	Belem	2501906
6415	2586	Belem do Brejo do Cruz	2502003
6416	2586	Bernardino Batista	2502052
6417	2586	Boa Ventura	2502102
6418	2586	Boa Vista	2502151
6419	2586	Bom Jesus	2502201
6420	2586	Bom Sucesso	2502300
6421	2586	Bonito de Santa Fe	2502409
6422	2586	Boqueirao	2502508
6423	2586	Igaracy	2502607
6424	2586	Borborema	2502706
6425	2586	Brejo do Cruz	2502805
6426	2586	Brejo dos Santos	2502904
6427	2586	Caapora	2503001
6428	2586	Cabaceiras	2503100
6429	2586	Cabedelo	2503209
6430	2586	Cachoeira dos Indios	2503308
6431	2586	Cacimba de Areia	2503407
6432	2586	Cacimba de Dentro	2503506
6433	2586	Cacimbas	2503555
6434	2586	Caicara	2503605
6435	2586	Cajazeiras	2503704
6436	2586	Cajazeirinhas	2503753
6437	2586	Caldas Brandao	2503803
6438	2586	Camalau	2503902
6439	2586	Campina Grande	2504009
6440	2586	Capim	2504033
6441	2586	Caraubas	2504074
6442	2586	Carrapateira	2504108
6443	2586	Casserengue	2504157
6444	2586	Catingueira	2504207
6445	2586	Catole do Rocha	2504306
6446	2586	Caturite	2504355
6447	2586	Conceicao	2504405
6448	2586	Condado	2504504
6449	2586	Conde	2504603
6450	2586	Congo	2504702
6451	2586	Coremas	2504801
6452	2586	Coxixola	2504850
6453	2586	Cruz do Espirito Santo	2504900
6454	2586	Cubati	2505006
6455	2586	Cuite	2505105
6456	2586	Cuitegi	2505204
6457	2586	Cuite de Mamanguape	2505238
6458	2586	Curral de Cima	2505279
6459	2586	Curral Velho	2505303
6460	2586	Damiao	2505352
6461	2586	Desterro	2505402
6462	2586	Vista Serrana	2505501
6463	2586	Diamante	2505600
6464	2586	Dona Ines	2505709
6465	2586	Duas Estradas	2505808
6466	2586	Emas	2505907
6467	2586	Esperanca	2506004
6468	2586	Fagundes	2506103
6469	2586	Frei Martinho	2506202
6470	2586	Gado Bravo	2506251
6471	2586	Guarabira	2506301
6472	2586	Gurinhem	2506400
6473	2586	Gurjao	2506509
6474	2586	Ibiara	2506608
6475	2586	Imaculada	2506707
6476	2586	Inga	2506806
6477	2586	Itabaiana	2506905
6478	2586	Itaporanga	2507002
6479	2586	Itapororoca	2507101
6480	2586	Itatuba	2507200
6481	2586	Jacarau	2507309
6482	2586	Jerico	2507408
6483	2586	Joao Pessoa	2507507
6484	2586	Juarez Tavora	2507606
6485	2586	Juazeirinho	2507705
6486	2586	Junco do Serido	2507804
6487	2586	Juripiranga	2507903
6488	2586	Juru	2508000
6489	2586	Lagoa	2508109
6490	2586	Lagoa de Dentro	2508208
6491	2586	Lagoa Seca	2508307
6492	2586	Lastro	2508406
6493	2586	Livramento	2508505
6494	2586	Logradouro	2508554
6495	2586	Lucena	2508604
6496	2586	Mae d'Agua	2508703
6497	2586	Malta	2508802
6498	2586	Mamanguape	2508901
6499	2586	Manaira	2509008
6500	2586	Marcacao	2509057
6501	2586	Mari	2509107
6502	2586	Marizopolis	2509156
6503	2586	Massaranduba	2509206
6504	2586	Mataraca	2509305
6505	2586	Matinhas	2509339
6506	2586	Mato Grosso	2509370
6507	2586	Matureia	2509396
6508	2586	Mogeiro	2509404
6509	2586	Montadas	2509503
6510	2586	Monte Horebe	2509602
6511	2586	Monteiro	2509701
6512	2586	Mulungu	2509800
6513	2586	Natuba	2509909
6514	2586	Nazarezinho	2510006
6515	2586	Nova Floresta	2510105
6516	2586	Nova Olinda	2510204
6517	2586	Nova Palmeira	2510303
6518	2586	Olho d'Agua	2510402
6519	2586	Olivedos	2510501
6520	2586	Ouro Velho	2510600
6521	2586	Parari	2510659
6522	2586	Passagem	2510709
6523	2586	Patos	2510808
6524	2586	Paulista	2510907
6525	2586	Pedra Branca	2511004
6526	2586	Pedra Lavrada	2511103
6527	2586	Pedras de Fogo	2511202
6528	2586	Pianco	2511301
6529	2586	Picui	2511400
6530	2586	Pilar	2511509
6531	2586	Piloes	2511608
6532	2586	Piloezinhos	2511707
6533	2586	Pirpirituba	2511806
6534	2586	Pitimbu	2511905
6535	2586	Pocinhos	2512002
6536	2586	Poco Dantas	2512036
6537	2586	Poco de Jose de Moura	2512077
6538	2586	Pombal	2512101
6539	2586	Prata	2512200
6540	2586	Princesa Isabel	2512309
6541	2586	Puxinana	2512408
6542	2586	Queimadas	2512507
6543	2586	Quixaba	2512606
6544	2586	Remigio	2512705
6545	2586	Pedro Regis	2512721
6546	2586	Riachao	2512747
6547	2586	Assis Chateaubriand	2512754
6548	2586	Riachao do Poco	2512762
6549	2586	Riacho de Santo Antonio	2512788
6550	2586	Riacho dos Cavalos	2512804
6551	2586	Rio Tinto	2512903
6552	2586	Salgadinho	2513000
6553	2586	Salgado de Sao Felix	2513109
6554	2586	Santa Cecilia de Umbuzeiro	2513158
6555	2586	Santa Cruz	2513208
6556	2586	Santa Helena	2513307
6557	2586	Santa Ines	2513356
6558	2586	Santa Luzia	2513406
6559	2586	Santana de Mangueira	2513505
6560	2586	Santana dos Garrotes	2513604
6561	2586	Santarem	2513653
6562	2586	Santa Rita	2513703
6563	2586	Santa Teresinha	2513802
6564	2586	Santo Andre	2513851
6565	2586	Sao Bento	2513901
6566	2586	Sao Bento de Pombal	2513927
6567	2586	Sao Domingos do Cariri	2513943
6568	2586	São Domingos de Pombal	2513968
6569	2586	Sao Francisco	2513984
6570	2586	Sao Joao do Cariri	2514008
6571	2586	Sao Joao do Tigre	2514107
6572	2586	Sao Jose da Lagoa Tapada	2514206
6573	2586	Sao Jose de Caiana	2514305
6574	2586	Sao Jose de Espinharas	2514404
6575	2586	Sao Jose dos Ramos	2514453
6576	2586	Sao Jose de Piranhas	2514503
6577	2586	Sao Jose de Princesa	2514552
6578	2586	Sao Jose do Bonfim	2514602
6579	2586	Sao Jose do Brejo do Cruz	2514651
6580	2586	Sao Jose do Sabugi	2514701
6581	2586	Sao Jose dos Cordeiros	2514800
6582	2586	Sao Mamede	2514909
6583	2586	Sao Miguel de Taipu	2515005
6584	2586	Sao Sebastiao de Lagoa de Roca	2515104
6585	2586	Sao Sebastiao do Umbuzeiro	2515203
6586	2586	Sape	2515302
6587	2586	Serido	2515401
6588	2586	Serra Branca	2515500
6589	2586	Serra da Raiz	2515609
6590	2586	Serra Grande	2515708
6591	2586	Serra Redonda	2515807
6592	2586	Serraria	2515906
6593	2586	Sertaozinho	2515930
6594	2586	Sobrado	2515971
6595	2586	Solanea	2516003
6596	2586	Soledade	2516102
6597	2586	Sossego	2516151
6598	2586	Sousa	2516201
6599	2586	Sume	2516300
6600	2586	Tacima	2516409
6601	2586	Taperoa	2516508
6602	2586	Tavares	2516607
6603	2586	Teixeira	2516706
6604	2586	Tenorio	2516755
6605	2586	Triunfo	2516805
6606	2586	Uirauna	2516904
6607	2586	Umbuzeiro	2517001
6608	2586	Varzea	2517100
6609	2586	Vieiropolis	2517209
6610	2586	Zabele	2517407
6611	2588	Abreu e Lima	2600054
6612	2588	Afogados da Ingazeira	2600104
6613	2588	Afranio	2600203
6614	2588	Agrestina	2600302
6615	2588	Agua Preta	2600401
6616	2588	Aguas Belas	2600500
6617	2588	Alagoinha	2600609
6618	2588	Alianca	2600708
6619	2588	Altinho	2600807
6620	2588	Amaraji	2600906
6621	2588	Angelim	2601003
6622	2588	Aracoiaba	2601052
6623	2588	Araripina	2601102
6624	2588	Arcoverde	2601201
6625	2588	Barra de Guabiraba	2601300
6626	2588	Barreiros	2601409
6627	2588	Belem de Maria	2601508
6628	2588	Belem de Sao Francisco	2601607
6629	2588	Belo Jardim	2601706
6630	2588	Betania	2601805
6631	2588	Bezerros	2601904
6632	2588	Bodoco	2602001
6633	2588	Bom Conselho	2602100
6634	2588	Bom Jardim	2602209
6635	2588	Bonito	2602308
6636	2588	Brejao	2602407
6637	2588	Brejinho	2602506
6638	2588	Brejo da Madre de Deus	2602605
6639	2588	Buenos Aires	2602704
6640	2588	Buique	2602803
6641	2588	Cabo de Santo Agostinho	2602902
6642	2588	Cabrobo	2603009
6643	2588	Cachoeirinha	2603108
6644	2588	Caetes	2603207
6645	2588	Calcado	2603306
6646	2588	Calumbi	2603405
6647	2588	Camaragibe	2603454
6648	2588	Camocim de Sao Felix	2603504
6649	2588	Camutanga	2603603
6650	2588	Canhotinho	2603702
6651	2588	Capoeiras	2603801
6652	2588	Carnaiba	2603900
6653	2588	Carnaubeira da Penha	2603926
6654	2588	Carpina	2604007
6655	2588	Caruaru	2604106
6656	2588	Casinhas	2604155
6657	2588	Catende	2604205
6658	2588	Cedro	2604304
6659	2588	Cha de Alegria	2604403
6660	2588	Cha Grande	2604502
6661	2588	Condado	2604601
6662	2588	Correntes	2604700
6663	2588	Cortes	2604809
6664	2588	Cumaru	2604908
6665	2588	Cupira	2605004
6666	2588	Custodia	2605103
6667	2588	Dormentes	2605152
6668	2588	Escada	2605202
6669	2588	Exu	2605301
6670	2588	Feira Nova	2605400
6671	2588	Fernando de Noronha	2605459
6672	2588	Ferreiros	2605509
6673	2588	Flores	2605608
6674	2588	Floresta	2605707
6675	2588	Frei Miguelinho	2605806
6676	2588	Gameleira	2605905
6677	2588	Garanhuns	2606002
6678	2588	Gloria do Goita	2606101
6679	2588	Goiana	2606200
6680	2588	Granito	2606309
6681	2588	Gravata	2606408
6682	2588	Iati	2606507
6683	2588	Ibimirim	2606606
6684	2588	Ibirajuba	2606705
6685	2588	Igarassu	2606804
6686	2588	Iguaraci	2606903
6687	2588	Inaja	2607000
6688	2588	Ingazeira	2607109
6689	2588	Ipojuca	2607208
6690	2588	Ipubi	2607307
6691	2588	Itacuruba	2607406
6692	2588	Itaiba	2607505
6693	2588	Itamaraca	2607604
6694	2588	Itambe	2607653
6695	2588	Itapetim	2607703
6696	2588	Itapissuma	2607752
6697	2588	Itaquitinga	2607802
6698	2588	Jaboatao dos Guararapes	2607901
6699	2588	Jaqueira	2607950
6700	2588	Jatauba	2608008
6701	2588	Jatoba	2608057
6702	2588	Joao Alfredo	2608107
6703	2588	Joaquim Nabuco	2608206
6704	2588	Jucati	2608255
6705	2588	Jupi	2608305
6706	2588	Jurema	2608404
6707	2588	Lagoa do Carro	2608453
6708	2588	Lagoa do Itaenga	2608503
6709	2588	Lagoa do Ouro	2608602
6710	2588	Lagoa dos Gatos	2608701
6711	2588	Lagoa Grande	2608750
6712	2588	Lajedo	2608800
6713	2588	Limoeiro	2608909
6714	2588	Macaparana	2609006
6715	2588	Machados	2609105
6716	2588	Manari	2609154
6717	2588	Maraial	2609204
6718	2588	Mirandiba	2609303
6719	2588	Moreno	2609402
6720	2588	Nazare da Mata	2609501
6721	2588	Olinda	2609600
6722	2588	Orobo	2609709
6723	2588	Oroco	2609808
6724	2588	Ouricuri	2609907
6725	2588	Palmares	2610004
6726	2588	Palmeirina	2610103
6727	2588	Panelas	2610202
6728	2588	Paranatama	2610301
6729	2588	Parnamirim	2610400
6730	2588	Passira	2610509
6731	2588	Paudalho	2610608
6732	2588	Paulista	2610707
6733	2588	Pedra	2610806
6734	2588	Pesqueira	2610905
6735	2588	Petrolandia	2611002
6736	2588	Petrolina	2611101
6737	2588	Pocao	2611200
6738	2588	Pombos	2611309
6739	2588	Primavera	2611408
6740	2588	Quipapa	2611507
6741	2588	Quixaba	2611533
6742	2588	Recife	2611606
6743	2588	Riacho das Almas	2611705
6744	2588	Ribeirao	2611804
6745	2588	Rio Formoso	2611903
6746	2588	Saire	2612000
6747	2588	Salgadinho	2612109
6748	2588	Salgueiro	2612208
6749	2588	Saloa	2612307
6750	2588	Sanharo	2612406
6751	2588	Santa Cruz	2612455
6752	2588	Santa Cruz da Baixa Verde	2612471
6753	2588	Santa Cruz do Capibaribe	2612505
6754	2588	Santa Filomena	2612554
6755	2588	Santa Maria da Boa Vista	2612604
6756	2588	Santa Maria do Cambuca	2612703
6757	2588	Santa Terezinha	2612802
6758	2588	Sao Benedito do Sul	2612901
6759	2588	Sao Bento do Una	2613008
6760	2588	Sao Caitano	2613107
6761	2588	Sao Joao	2613206
6762	2588	Sao Joaquim do Monte	2613305
6763	2588	Sao Jose da Coroa Grande	2613404
6764	2588	Sao Jose do Belmonte	2613503
6765	2588	Sao Jose do Egito	2613602
6766	2588	Sao Lourenco da Mata	2613701
6767	2588	Sao Vicente Ferrer	2613800
6768	2588	Serra Talhada	2613909
6769	2588	Serrita	2614006
6770	2588	Sertania	2614105
6771	2588	Sirinhaem	2614204
6772	2588	Moreilandia	2614303
6773	2588	Solidao	2614402
6774	2588	Surubim	2614501
6775	2588	Tabira	2614600
6776	2588	Tacaimbo	2614709
6777	2588	Tacaratu	2614808
6778	2588	Tamandare	2614857
6779	2588	Taquaritinga do Norte	2615003
6780	2588	Terezinha	2615102
6781	2588	Terra Nova	2615201
6782	2588	Timbauba	2615300
6783	2588	Toritama	2615409
6784	2588	Tracunhaem	2615508
6785	2588	Trindade	2615607
6786	2588	Triunfo	2615706
6787	2588	Tupanatinga	2615805
6788	2588	Tuparetama	2615904
6789	2588	Venturosa	2616001
6790	2588	Verdejante	2616100
6791	2588	Vertente do Lerio	2616183
6792	2588	Vertentes	2616209
6793	2588	Vicencia	2616308
6794	2588	Vitoria de Santo Antao	2616407
6795	2588	Xexeu	2616506
6796	2574	Agua Branca	2700102
6797	2574	Anadia	2700201
6798	2574	Arapiraca	2700300
6799	2574	Atalaia	2700409
6800	2574	Barra de Santo Antonio	2700508
6801	2574	Barra de Sao Miguel	2700607
6802	2574	Batalha	2700706
6803	2574	Belem	2700805
6804	2574	Belo Monte	2700904
6805	2574	Boca da Mata	2701001
6806	2574	Branquinha	2701100
6807	2574	Cacimbinhas	2701209
6808	2574	Cajueiro	2701308
6809	2574	Campestre	2701357
6810	2574	Campo Alegre	2701407
6811	2574	Campo Grande	2701506
6812	2574	Canapi	2701605
6813	2574	Capela	2701704
6814	2574	Carneiros	2701803
6815	2574	Cha Preta	2701902
6816	2574	Coite do Noia	2702009
6817	2574	Colonia Leopoldina	2702108
6818	2574	Coqueiro Seco	2702207
6819	2574	Coruripe	2702306
6820	2574	Craibas	2702355
6821	2574	Delmiro Gouveia	2702405
6822	2574	Dois Riachos	2702504
6823	2574	Estrela de Alagoas	2702553
6824	2574	Feira Grande	2702603
6825	2574	Feliz Deserto	2702702
6826	2574	Flexeiras	2702801
6827	2574	Girau do Ponciano	2702900
6828	2574	Ibateguara	2703007
6829	2574	Igaci	2703106
6830	2574	Igreja Nova	2703205
6831	2574	Inhapi	2703304
6832	2574	Jacare dos Homens	2703403
6833	2574	Jacuipe	2703502
6834	2574	Japaratinga	2703601
6835	2574	Jaramataia	2703700
6836	2574	JEQUIA DA PRAIA	2703759
6837	2574	Joaquim Gomes	2703809
6838	2574	Jundia	2703908
6839	2574	Junqueiro	2704005
6840	2574	Lagoa da Canoa	2704104
6841	2574	Limoeiro de Anadia	2704203
6842	2574	Maceio	2704302
6843	2574	Major Isidoro	2704401
6844	2574	Maragogi	2704500
6845	2574	Maravilha	2704609
6846	2574	Marechal Deodoro	2704708
6847	2574	Maribondo	2704807
6848	2574	Mar Vermelho	2704906
6849	2574	Mata Grande	2705002
6850	2574	Matriz de Camaragibe	2705101
6851	2574	Messias	2705200
6852	2574	Minador do Negrao	2705309
6853	2574	Monteiropolis	2705408
6854	2574	Murici	2705507
6855	2574	Novo Lino	2705606
6856	2574	Olho d'Agua das Flores	2705705
6857	2574	Olho d'Agua do Casado	2705804
6858	2574	Olho d'Agua Grande	2705903
6859	2574	Olivenca	2706000
6860	2574	Ouro Branco	2706109
6861	2574	Palestina	2706208
6862	2574	Palmeira dos Indios	2706307
6863	2574	Pao de Acucar	2706406
6864	2574	Pariconha	2706422
6865	2574	Paripueira	2706448
6866	2574	Passo de Camaragibe	2706505
6867	2574	Paulo Jacinto	2706604
6868	2574	Penedo	2706703
6869	2574	Piacabucu	2706802
6870	2574	Pilar	2706901
6871	2574	Pindoba	2707008
6872	2574	Piranhas	2707107
6873	2574	Poco das Trincheiras	2707206
6874	2574	Porto Calvo	2707305
6875	2574	Porto de Pedras	2707404
6876	2574	Porto Real do Colegio	2707503
6877	2574	Quebrangulo	2707602
6878	2574	Rio Largo	2707701
6879	2574	Roteiro	2707800
6880	2574	Santa Luzia do Norte	2707909
6881	2574	Santana do Ipanema	2708006
6882	2574	Santana do Mundau	2708105
6883	2574	Sao Bras	2708204
6884	2574	Sao Jose da Laje	2708303
6885	2574	Sao Jose da Tapera	2708402
6886	2574	Sao Luis do Quitunde	2708501
6887	2574	Sao Miguel dos Campos	2708600
6888	2574	Sao Miguel dos Milagres	2708709
6889	2574	Sao Sebastiao	2708808
6890	2574	Satuba	2708907
6891	2574	Senador Rui Palmeira	2708956
6892	2574	Tanque d'Arca	2709004
6893	2574	Taquarana	2709103
6894	2574	Teotonio Vilela	2709152
6895	2574	Traipu	2709202
6896	2574	Uniao dos Palmares	2709301
6897	2574	Vicosa	2709400
6898	2597	Amparo de Sao Francisco	2800100
6899	2597	Aquidaba	2800209
6900	2597	Aracaju	2800308
6901	2597	Araua	2800407
6902	2597	Areia Branca	2800506
6903	2597	Barra dos Coqueiros	2800605
6904	2597	Boquim	2800670
6905	2597	Brejo Grande	2800704
6906	2597	Campo do Brito	2801009
6907	2597	Canhoba	2801108
6908	2597	Caninde de Sao Francisco	2801207
6909	2597	Capela	2801306
6910	2597	Carira	2801405
6911	2597	Carmopolis	2801504
6912	2597	Cedro de Sao Joao	2801603
6913	2597	Cristinapolis	2801702
6914	2597	Cumbe	2801900
6915	2597	Divina Pastora	2802007
6916	2597	Estancia	2802106
6917	2597	Feira Nova	2802205
6918	2597	Frei Paulo	2802304
6919	2597	Gararu	2802403
6920	2597	General Maynard	2802502
6921	2597	Gracho Cardoso	2802601
6922	2597	Ilha das Flores	2802700
6923	2597	Indiaroba	2802809
6924	2597	Itabaiana	2802908
6925	2597	Itabaianinha	2803005
6926	2597	Itabi	2803104
6927	2597	Itaporanga d'Ajuda	2803203
6928	2597	Japaratuba	2803302
6929	2597	Japoata	2803401
6930	2597	Lagarto	2803500
6931	2597	Laranjeiras	2803609
6932	2597	Macambira	2803708
6933	2597	Malhada dos Bois	2803807
6934	2597	Malhador	2803906
6935	2597	Maruim	2804003
6936	2597	Moita Bonita	2804102
6937	2597	Monte Alegre de Sergipe	2804201
6938	2597	Muribeca	2804300
6939	2597	Neopolis	2804409
6940	2597	Nossa Senhora Aparecida	2804458
6941	2597	Nossa Senhora da Gloria	2804508
6942	2597	Nossa Senhora das Dores	2804607
6943	2597	Nossa Senhora de Lourdes	2804706
6944	2597	Nossa Senhora do Socorro	2804805
6945	2597	Pacatuba	2804904
6946	2597	Pedra Mole	2805000
6947	2597	Pedrinhas	2805109
6948	2597	Pinhao	2805208
6949	2597	Pirambu	2805307
6950	2597	Poco Redondo	2805406
6951	2597	Poco Verde	2805505
6952	2597	Porto da Folha	2805604
6953	2597	Propria	2805703
6954	2597	Riachao do Dantas	2805802
6955	2597	Riachuelo	2805901
6956	2597	Ribeiropolis	2806008
6957	2597	Rosario do Catete	2806107
6958	2597	Salgado	2806206
6959	2597	Santa Luzia do Itanhy	2806305
6960	2597	Santana do Sao Francisco	2806404
6961	2597	Santa Rosa de Lima	2806503
6962	2597	Santo Amaro das Brotas	2806602
6963	2597	Sao Cristovao	2806701
6964	2597	Sao Domingos	2806800
6965	2597	Sao Francisco	2806909
6966	2597	Sao Miguel do Aleixo	2807006
6967	2597	Simao Dias	2807105
6968	2597	Siriri	2807204
6969	2597	Telha	2807303
6970	2597	Tobias Barreto	2807402
6971	2597	Tomar do Geru	2807501
6972	2597	Umbauba	2807600
6973	2584	Abadia dos Dourados	3100104
6974	2584	Abaete	3100203
6975	2584	Abre Campo	3100302
6976	2584	Acaiaca	3100401
6977	2584	Acucena	3100500
6978	2584	Agua Boa	3100609
6979	2584	Agua Comprida	3100708
6980	2584	Aguanil	3100807
6981	2584	Aguas Formosas	3100906
6982	2584	Aguas Vermelhas	3101003
6983	2584	Aimores	3101102
6984	2584	Aiuruoca	3101201
6985	2584	Alagoa	3101300
6986	2584	Albertina	3101409
6987	2584	Alem Paraiba	3101508
6988	2584	Alfenas	3101607
6989	2584	Alfredo Vasconcelos	3101631
6990	2584	Almenara	3101706
6991	2584	Alpercata	3101805
6992	2584	Alpinopolis	3101904
6993	2584	Alterosa	3102001
6994	2584	Alto Caparao	3102050
6995	2584	Alto Rio Doce	3102100
6996	2584	Alvarenga	3102209
6997	2584	Alvinopolis	3102308
6998	2584	Alvorada de Minas	3102407
6999	2584	Amparo do Serra	3102506
7000	2584	Andradas	3102605
7001	2584	Cachoeira de Pajeu	3102704
7002	2584	Andrelandia	3102803
7003	2584	Angelandia	3102852
7004	2584	Antonio Carlos	3102902
7005	2584	Antonio Dias	3103009
7006	2584	Antonio Prado de Minas	3103108
7007	2584	Aracai	3103207
7008	2584	Aracitaba	3103306
7009	2584	Aracuai	3103405
7010	2584	Araguari	3103504
7011	2584	Arantina	3103603
7012	2584	Araponga	3103702
7013	2584	Arapora	3103751
7014	2584	Arapua	3103801
7015	2584	Araujos	3103900
7016	2584	Araxa	3104007
7017	2584	Arceburgo	3104106
7018	2584	Arcos	3104205
7019	2584	Areado	3104304
7020	2584	Argirita	3104403
7021	2584	Aricanduva	3104452
7022	2584	Arinos	3104502
7023	2584	Astolfo Dutra	3104601
7024	2584	Ataleia	3104700
7025	2584	Augusto de Lima	3104809
7026	2584	Baependi	3104908
7027	2584	Baldim	3105004
7028	2584	Bambui	3105103
7029	2584	Bandeira	3105202
7030	2584	Bandeira do Sul	3105301
7031	2584	Barao de Cocais	3105400
7032	2584	Barao de Monte Alto	3105509
7033	2584	Barbacena	3105608
7034	2584	Barra Longa	3105707
7035	2584	Barroso	3105905
7036	2584	Bela Vista de Minas	3106002
7037	2584	Belmiro Braga	3106101
7038	2584	Belo Horizonte	3106200
7039	2584	Belo Oriente	3106309
7040	2584	Belo Vale	3106408
7041	2584	Berilo	3106507
7042	2584	Bertopolis	3106606
7043	2584	Berizal	3106655
7044	2584	Betim	3106705
7045	2584	Bias Fortes	3106804
7046	2584	Bicas	3106903
7047	2584	Biquinhas	3107000
7048	2584	Boa Esperanca	3107109
7049	2584	Bocaina de Minas	3107208
7050	2584	Bocaiuva	3107307
7051	2584	Bom Despacho	3107406
7052	2584	Bom Jardim de Minas	3107505
7053	2584	Bom Jesus da Penha	3107604
7054	2584	Bom Jesus do Amparo	3107703
7055	2584	Bom Jesus do Galho	3107802
7056	2584	Bom Repouso	3107901
7057	2584	Bom Sucesso	3108008
7058	2584	Bonfim	3108107
7059	2584	Bonfinopolis de Minas	3108206
7060	2584	Bonito de Minas	3108255
7061	2584	Borda da Mata	3108305
7062	2584	Botelhos	3108404
7063	2584	Botumirim	3108503
7064	2584	Brasilandia de Minas	3108552
7065	2584	Brasilia de Minas	3108602
7066	2584	Bras Pires	3108701
7067	2584	Braunas	3108800
7068	2584	Brasopolis	3108909
7069	2584	Brumadinho	3109006
7070	2584	Bueno Brandao	3109105
7071	2584	Buenopolis	3109204
7072	2584	Bugre	3109253
7073	2584	Buritis	3109303
7074	2584	Buritizeiro	3109402
7075	2584	Cabeceira Grande	3109451
7076	2584	Cabo Verde	3109501
7077	2584	Cachoeira da Prata	3109600
7078	2584	Cachoeira de Minas	3109709
7079	2584	Cachoeira Dourada	3109808
7080	2584	Caetanopolis	3109907
7081	2584	Caete	3110004
7082	2584	Caiana	3110103
7083	2584	Cajuri	3110202
7084	2584	Caldas	3110301
7085	2584	Camacho	3110400
7086	2584	Camanducaia	3110509
7087	2584	Cambui	3110608
7088	2584	Cambuquira	3110707
7089	2584	Campanario	3110806
7090	2584	Campanha	3110905
7091	2584	Campestre	3111002
7092	2584	Campina Verde	3111101
7093	2584	Campo Azul	3111150
7094	2584	Campo Belo	3111200
7095	2584	Campo do Meio	3111309
7096	2584	Campo Florido	3111408
7097	2584	Campos Altos	3111507
7098	2584	Campos Gerais	3111606
7099	2584	Canaa	3111705
7100	2584	Canapolis	3111804
7101	2584	Cana Verde	3111903
7102	2584	Candeias	3112000
7103	2584	Cantagalo	3112059
7104	2584	Caparao	3112109
7105	2584	Capela Nova	3112208
7106	2584	Capelinha	3112307
7107	2584	Capetinga	3112406
7108	2584	Capim Branco	3112505
7109	2584	Capinopolis	3112604
7110	2584	Capitao Andrade	3112653
7111	2584	Capitao Eneas	3112703
7112	2584	Capitolio	3112802
7113	2584	Caputira	3112901
7114	2584	Carai	3113008
7115	2584	Caranaiba	3113107
7116	2584	Carandai	3113206
7117	2584	Carangola	3113305
7118	2584	Caratinga	3113404
7119	2584	Carbonita	3113503
7120	2584	Careacu	3113602
7121	2584	Carlos Chagas	3113701
7122	2584	Carmesia	3113800
7123	2584	Carmo da Cachoeira	3113909
7124	2584	Carmo da Mata	3114006
7125	2584	Carmo de Minas	3114105
7126	2584	Carmo do Cajuru	3114204
7127	2584	Carmo do Paranaiba	3114303
7128	2584	Carmo do Rio Claro	3114402
7129	2584	Carmopolis de Minas	3114501
7130	2584	Carneirinho	3114550
7131	2584	Carrancas	3114600
7132	2584	Carvalhopolis	3114709
7133	2584	Carvalhos	3114808
7134	2584	Casa Grande	3114907
7135	2584	Cascalho Rico	3115003
7136	2584	Cassia	3115102
7137	2584	Conceicao da Barra de Minas	3115201
7138	2584	Cataguases	3115300
7139	2584	Catas Altas	3115359
7140	2584	Catas Altas da Noruega	3115409
7141	2584	Catuji	3115458
7142	2584	Catuti	3115474
7143	2584	Caxambu	3115508
7144	2584	Cedro do Abaete	3115607
7145	2584	Central de Minas	3115706
7146	2584	Centralina	3115805
7147	2584	Chacara	3115904
7148	2584	Chale	3116001
7149	2584	Chapada do Norte	3116100
7150	2584	Chapada Gaucha	3116159
7151	2584	Chiador	3116209
7152	2584	Cipotanea	3116308
7153	2584	Claraval	3116407
7154	2584	Claro dos Pocoes	3116506
7155	2584	Claudio	3116605
7156	2584	Coimbra	3116704
7157	2584	Coluna	3116803
7158	2584	Comendador Gomes	3116902
7159	2584	Comercinho	3117009
7160	2584	Conceicao da Aparecida	3117108
7161	2584	Conceicao das Pedras	3117207
7162	2584	Conceicao das Alagoas	3117306
7163	2584	Conceicao de Ipanema	3117405
7164	2584	Conceicao do Mato Dentro	3117504
7165	2584	Conceicao do Para	3117603
7166	2584	Conceicao do Rio Verde	3117702
7167	2584	Conceicao dos Ouros	3117801
7168	2584	Conego Marinho	3117836
7169	2584	Confins	3117876
7170	2584	Congonhal	3117900
7171	2584	Congonhas	3118007
7172	2584	Congonhas do Norte	3118106
7173	2584	Conquista	3118205
7174	2584	Conselheiro Lafaiete	3118304
7175	2584	Conselheiro Pena	3118403
7176	2584	Consolacao	3118502
7177	2584	Contagem	3118601
7178	2584	Coqueiral	3118700
7179	2584	Coracao de Jesus	3118809
7180	2584	Cordisburgo	3118908
7181	2584	Cordislandia	3119005
7182	2584	Corinto	3119104
7183	2584	Coroaci	3119203
7184	2584	Coromandel	3119302
7185	2584	Coronel Fabriciano	3119401
7186	2584	Coronel Murta	3119500
7187	2584	Coronel Pacheco	3119609
7188	2584	Coronel Xavier Chaves	3119708
7189	2584	Corrego Danta	3119807
7190	2584	Corrego do Bom Jesus	3119906
7191	2584	Corrego Fundo	3119955
7192	2584	Corrego Novo	3120003
7193	2584	Couto de Magalhaes de Minas	3120102
7194	2584	Crisolita	3120151
7195	2584	Cristais	3120201
7196	2584	Cristalia	3120300
7197	2584	Cristiano Otoni	3120409
7198	2584	Cristina	3120508
7199	2584	Crucilandia	3120607
7200	2584	Cruzeiro da Fortaleza	3120706
7201	2584	Cruzilia	3120805
7202	2584	Cuparaque	3120839
7203	2584	Curral de Dentro	3120870
7204	2584	Curvelo	3120904
7205	2584	Datas	3121001
7206	2584	Delfim Moreira	3121100
7207	2584	Delfinopolis	3121209
7208	2584	Delta	3121258
7209	2584	Descoberto	3121308
7210	2584	Desterro de Entre Rios	3121407
7211	2584	Desterro do Melo	3121506
7212	2584	Diamantina	3121605
7213	2584	Diogo de Vasconcelos	3121704
7214	2584	Dionisio	3121803
7215	2584	Divinesia	3121902
7216	2584	Divino	3122009
7217	2584	Divino das Laranjeiras	3122108
7218	2584	Divinolandia de Minas	3122207
7219	2584	Divinopolis	3122306
7220	2584	Divisa Alegre	3122355
7221	2584	Divisa Nova	3122405
7222	2584	Divisopolis	3122454
7223	2584	Dom Bosco	3122470
7224	2584	Dom Cavati	3122504
7225	2584	Dom Joaquim	3122603
7226	2584	Dom Silverio	3122702
7227	2584	Dom Vicoso	3122801
7228	2584	Dona Euzebia	3122900
7229	2584	Dores de Campos	3123007
7230	2584	Dores de Guanhaes	3123106
7231	2584	Dores do Indaia	3123205
7232	2584	Dores do Turvo	3123304
7233	2584	Doresopolis	3123403
7234	2584	Douradoquara	3123502
7235	2584	Durande	3123528
7236	2584	Eloi Mendes	3123601
7237	2584	Engenheiro Caldas	3123700
7238	2584	Engenheiro Navarro	3123809
7239	2584	Entre Folhas	3123858
7240	2584	Entre Rios de Minas	3123908
7241	2584	Ervalia	3124005
7242	2584	Esmeraldas	3124104
7243	2584	Espera Feliz	3124203
7244	2584	Espinosa	3124302
7245	2584	Espirito Santo do Dourado	3124401
7246	2584	Estiva	3124500
7247	2584	Estrela Dalva	3124609
7248	2584	Estrela do Indaia	3124708
7249	2584	Estrela do Sul	3124807
7250	2584	Eugenopolis	3124906
7251	2584	Ewbank da Camara	3125002
7252	2584	Extrema	3125101
7253	2584	Fama	3125200
7254	2584	Faria Lemos	3125309
7255	2584	Felicio dos Santos	3125408
7256	2584	Sao Goncalo do Rio Preto	3125507
7257	2584	Felisburgo	3125606
7258	2584	Felixlandia	3125705
7259	2584	Fernandes Tourinho	3125804
7260	2584	Ferros	3125903
7261	2584	Fervedouro	3125952
7262	2584	Florestal	3126000
7263	2584	Formiga	3126109
7264	2584	Formoso	3126208
7265	2584	Fortaleza de Minas	3126307
7266	2584	Fortuna de Minas	3126406
7267	2584	Francisco Badaro	3126505
7268	2584	Francisco Dumont	3126604
7269	2584	Francisco Sa	3126703
7270	2584	Franciscopolis	3126752
7271	2584	Frei Gaspar	3126802
7272	2584	Frei Inocencio	3126901
7273	2584	Frei Lagonegro	3126950
7274	2584	Fronteira	3127008
7275	2584	Fronteira dos Vales	3127057
7276	2584	Fruta de Leite	3127073
7277	2584	Frutal	3127107
7278	2584	Funilandia	3127206
7279	2584	Galileia	3127305
7280	2584	Gameleiras	3127339
7281	2584	Glaucilandia	3127354
7282	2584	Goiabeira	3127370
7283	2584	Goiana	3127388
7284	2584	Goncalves	3127404
7285	2584	Gonzaga	3127503
7286	2584	Gouvea	3127602
7287	2584	Governador Valadares	3127701
7288	2584	Grao Mogol	3127800
7289	2584	Grupiara	3127909
7290	2584	Guanhaes	3128006
7291	2584	Guape	3128105
7292	2584	Guaraciaba	3128204
7293	2584	Guaraciama	3128253
7294	2584	Guaranesia	3128303
7295	2584	Guarani	3128402
7296	2584	Guarara	3128501
7297	2584	Guarda-Mor	3128600
7298	2584	Guaxupe	3128709
7299	2584	Guidoval	3128808
7300	2584	Guimarania	3128907
7301	2584	Guiricema	3129004
7302	2584	Gurinhata	3129103
7303	2584	Heliodora	3129202
7304	2584	Iapu	3129301
7305	2584	Ibertioga	3129400
7306	2584	Ibia	3129509
7307	2584	Ibiai	3129608
7308	2584	Ibiracatu	3129657
7309	2584	Ibiraci	3129707
7310	2584	Ibirite	3129806
7311	2584	Ibitiura de Minas	3129905
7312	2584	Ibituruna	3130002
7313	2584	Icarai de Minas	3130051
7314	2584	Igarape	3130101
7315	2584	Igaratinga	3130200
7316	2584	Iguatama	3130309
7317	2584	Ijaci	3130408
7318	2584	Ilicinea	3130507
7319	2584	Imbe de Minas	3130556
7320	2584	Inconfidentes	3130606
7321	2584	Indaiabira	3130655
7322	2584	Indianopolis	3130705
7323	2584	Ingai	3130804
7324	2584	Inhapim	3130903
7325	2584	Inhauma	3131000
7326	2584	Inimutaba	3131109
7327	2584	Ipaba	3131158
7328	2584	Ipanema	3131208
7329	2584	Ipatinga	3131307
7330	2584	Ipiacu	3131406
7331	2584	Ipuiuna	3131505
7332	2584	Irai de Minas	3131604
7333	2584	Itabira	3131703
7334	2584	Itabirinha de Mantena	3131802
7335	2584	Itabirito	3131901
7336	2584	Itacambira	3132008
7337	2584	Itacarambi	3132107
7338	2584	Itaguara	3132206
7339	2584	Itaipe	3132305
7340	2584	Itajuba	3132404
7341	2584	Itamarandiba	3132503
7342	2584	Itamarati de Minas	3132602
7343	2584	Itambacuri	3132701
7344	2584	Itambe do Mato Dentro	3132800
7345	2584	Itamogi	3132909
7346	2584	Itamonte	3133006
7347	2584	Itanhandu	3133105
7348	2584	Itanhomi	3133204
7349	2584	Itaobim	3133303
7350	2584	Itapagipe	3133402
7351	2584	Itapecerica	3133501
7352	2584	Itapeva	3133600
7353	2584	Itatiaiucu	3133709
7354	2584	Itau de Minas	3133758
7355	2584	Itauna	3133808
7356	2584	Itaverava	3133907
7357	2584	Itinga	3134004
7358	2584	Itueta	3134103
7359	2584	Ituiutaba	3134202
7360	2584	Itumirim	3134301
7361	2584	Iturama	3134400
7362	2584	Itutinga	3134509
7363	2584	Jaboticatubas	3134608
7364	2584	Jacinto	3134707
7365	2584	Jacui	3134806
7366	2584	Jacutinga	3134905
7367	2584	Jaguaracu	3135001
7368	2584	Jaiba	3135050
7369	2584	Jampruca	3135076
7370	2584	Janauba	3135100
7371	2584	Januaria	3135209
7372	2584	Japaraiba	3135308
7373	2584	Japonvar	3135357
7374	2584	Jeceaba	3135407
7375	2584	Jenipapo de Minas	3135456
7376	2584	Jequeri	3135506
7377	2584	Jequitai	3135605
7378	2584	Jequitiba	3135704
7379	2584	Jequitinhonha	3135803
7380	2584	Jesuania	3135902
7381	2584	Joaima	3136009
7382	2584	Joanesia	3136108
7383	2584	Joao Monlevade	3136207
7384	2584	Joao Pinheiro	3136306
7385	2584	Joaquim Felicio	3136405
7386	2584	Jordania	3136504
7387	2584	Jose Goncalves de Minas	3136520
7388	2584	Jose Raydan	3136553
7389	2584	Josenopolis	3136579
7390	2584	Nova Uniao	3136603
7391	2584	Juatuba	3136652
7392	2584	Juiz de Fora	3136702
7393	2584	Juramento	3136801
7394	2584	Juruaia	3136900
7395	2584	Juvenilia	3136959
7396	2584	Ladainha	3137007
7397	2584	Lagamar	3137106
7398	2584	Lagoa da Prata	3137205
7399	2584	Lagoa dos Patos	3137304
7400	2584	Lagoa Dourada	3137403
7401	2584	Lagoa Formosa	3137502
7402	2584	Lagoa Grande	3137536
7403	2584	Lagoa Santa	3137601
7404	2584	Lajinha	3137700
7405	2584	Lambari	3137809
7406	2584	Lamim	3137908
7407	2584	Laranjal	3138005
7408	2584	Lassance	3138104
7409	2584	Lavras	3138203
7410	2584	Leandro Ferreira	3138302
7411	2584	Leme do Prado	3138351
7412	2584	Leopoldina	3138401
7413	2584	Liberdade	3138500
7414	2584	Lima Duarte	3138609
7415	2584	Limeira do Oeste	3138625
7416	2584	Lontra	3138658
7417	2584	Luisburgo	3138674
7418	2584	Luislandia	3138682
7419	2584	Luminarias	3138708
7420	2584	Luz	3138807
7421	2584	Machacalis	3138906
7422	2584	Machado	3139003
7423	2584	Madre de Deus de Minas	3139102
7424	2584	Malacacheta	3139201
7425	2584	Mamonas	3139250
7426	2584	Manga	3139300
7427	2584	Manhuacu	3139409
7428	2584	Manhumirim	3139508
7429	2584	Mantena	3139607
7430	2584	Maravilhas	3139706
7431	2584	Mar de Espanha	3139805
7432	2584	Maria da Fe	3139904
7433	2584	Mariana	3140001
7434	2584	Marilac	3140100
7435	2584	Mario Campos	3140159
7436	2584	Maripa de Minas	3140209
7437	2584	Marlieria	3140308
7438	2584	Marmelopolis	3140407
7439	2584	Martinho Campos	3140506
7440	2584	Martins Soares	3140530
7441	2584	Mata Verde	3140555
7442	2584	Materlandia	3140605
7443	2584	Mateus Leme	3140704
7444	2584	Matias Barbosa	3140803
7445	2584	Matias Cardoso	3140852
7446	2584	Matipo	3140902
7447	2584	Mato Verde	3141009
7448	2584	Matozinhos	3141108
7449	2584	Matutina	3141207
7450	2584	Medeiros	3141306
7451	2584	Medina	3141405
7452	2584	Mendes Pimentel	3141504
7453	2584	Merces	3141603
7454	2584	Mesquita	3141702
7455	2584	Minas Novas	3141801
7456	2584	Minduri	3141900
7457	2584	Mirabela	3142007
7458	2584	Miradouro	3142106
7459	2584	Mirai	3142205
7460	2584	Miravania	3142254
7461	2584	Moeda	3142304
7462	2584	Moema	3142403
7463	2584	Monjolos	3142502
7464	2584	Monsenhor Paulo	3142601
7465	2584	Montalvania	3142700
7466	2584	Monte Alegre de Minas	3142809
7467	2584	Monte Azul	3142908
7468	2584	Monte Belo	3143005
7469	2584	Monte Carmelo	3143104
7470	2584	Monte Formoso	3143153
7471	2584	Monte Santo de Minas	3143203
7472	2584	Montes Claros	3143302
7473	2584	Monte Siao	3143401
7474	2584	Montezuma	3143450
7475	2584	Morada Nova de Minas	3143500
7476	2584	Morro da Garca	3143609
7477	2584	Morro do Pilar	3143708
7478	2584	Munhoz	3143807
7479	2584	Muriae	3143906
7480	2584	Mutum	3144003
7481	2584	Muzambinho	3144102
7482	2584	Nacip Raydan	3144201
7483	2584	Nanuque	3144300
7484	2584	Naque	3144359
7485	2584	Natalandia	3144375
7486	2584	Natercia	3144409
7487	2584	Nazareno	3144508
7488	2584	Nepomuceno	3144607
7489	2584	Ninheira	3144656
7490	2584	Nova Belem	3144672
7491	2584	Nova Era	3144706
7492	2584	Nova Lima	3144805
7493	2584	Nova Modica	3144904
7494	2584	Nova Ponte	3145000
7495	2584	Nova Porteirinha	3145059
7496	2584	Nova Resende	3145109
7497	2584	Nova Serrana	3145208
7498	2584	Novo Cruzeiro	3145307
7499	2584	Novo Oriente de Minas	3145356
7500	2584	Novorizonte	3145372
7501	2584	Olaria	3145406
7502	2584	Olhos-D'Agua	3145455
7503	2584	Olimpio Noronha	3145505
7504	2584	Oliveira	3145604
7505	2584	Oliveira Fortes	3145703
7506	2584	Onca de Pitangui	3145802
7507	2584	Oratorios	3145851
7508	2584	Orizania	3145877
7509	2584	Ouro Branco	3145901
7510	2584	Ouro Fino	3146008
7511	2584	Ouro Preto	3146107
7512	2584	Ouro Verde de Minas	3146206
7513	2584	Padre Carvalho	3146255
7514	2584	Padre Paraiso	3146305
7515	2584	Paineiras	3146404
7516	2584	Pains	3146503
7517	2584	Pai Pedro	3146552
7518	2584	Paiva	3146602
7519	2584	Palma	3146701
7520	2584	Palmopolis	3146750
7521	2584	Papagaios	3146909
7522	2584	Paracatu	3147006
7523	2584	Para de Minas	3147105
7524	2584	Paraguacu	3147204
7525	2584	Paraisopolis	3147303
7526	2584	Paraopeba	3147402
7527	2584	Passabem	3147501
7528	2584	Passa Quatro	3147600
7529	2584	Passa Tempo	3147709
7530	2584	Passa Vinte	3147808
7531	2584	Passos	3147907
7532	2584	Patis	3147956
7533	2584	Patos de Minas	3148004
7534	2584	Patrocinio	3148103
7535	2584	Patrocinio do Muriae	3148202
7536	2584	Paula Candido	3148301
7537	2584	Paulistas	3148400
7538	2584	Pavao	3148509
7539	2584	Pecanha	3148608
7540	2584	Pedra Azul	3148707
7541	2584	Pedra Bonita	3148756
7542	2584	Pedra do Anta	3148806
7543	2584	Pedra do Indaia	3148905
7544	2584	Pedra Dourada	3149002
7545	2584	Pedralva	3149101
7546	2584	Pedras de Maria da Cruz	3149150
7547	2584	Pedrinopolis	3149200
7548	2584	Pedro Leopoldo	3149309
7549	2584	Pedro Teixeira	3149408
7550	2584	Pequeri	3149507
7551	2584	Pequi	3149606
7552	2584	Perdigao	3149705
7553	2584	Perdizes	3149804
7554	2584	Perdoes	3149903
7555	2584	Periquito	3149952
7556	2584	Pescador	3150000
7557	2584	Piau	3150109
7558	2584	Piedade de Caratinga	3150158
7559	2584	Piedade de Ponte Nova	3150208
7560	2584	Piedade do Rio Grande	3150307
7561	2584	Piedade dos Gerais	3150406
7562	2584	Pimenta	3150505
7563	2584	Pingo D'Agua	3150539
7564	2584	Pintopolis	3150570
7565	2584	Piracema	3150604
7566	2584	Pirajuba	3150703
7567	2584	Piranga	3150802
7568	2584	Pirangucu	3150901
7569	2584	Piranguinho	3151008
7570	2584	Pirapetinga	3151107
7571	2584	Pirapora	3151206
7572	2584	Pirauba	3151305
7573	2584	Pitangui	3151404
7574	2584	Piui	3151503
7575	2584	Planura	3151602
7576	2584	Poco Fundo	3151701
7577	2584	Pocos de Caldas	3151800
7578	2584	Pocrane	3151909
7579	2584	Pompeu	3152006
7580	2584	Ponte Nova	3152105
7581	2584	Ponto Chique	3152131
7582	2584	Ponto dos Volantes	3152170
7583	2584	Porteirinha	3152204
7584	2584	Porto Firme	3152303
7585	2584	Pote	3152402
7586	2584	Pouso Alegre	3152501
7587	2584	Pouso Alto	3152600
7588	2584	Prados	3152709
7589	2584	Prata	3152808
7590	2584	Pratapolis	3152907
7591	2584	Pratinha	3153004
7592	2584	Presidente Bernardes	3153103
7593	2584	Presidente Juscelino	3153202
7594	2584	Presidente Kubitschek	3153301
7595	2584	Presidente Olegario	3153400
7596	2584	Alto Jequitiba	3153509
7597	2584	Prudente de Morais	3153608
7598	2584	Quartel Geral	3153707
7599	2584	Queluzita	3153806
7600	2584	Raposos	3153905
7601	2584	Raul Soares	3154002
7602	2584	Recreio	3154101
7603	2584	Reduto	3154150
7604	2584	Resende Costa	3154200
7605	2584	Resplendor	3154309
7606	2584	Ressaquinha	3154408
7607	2584	Riachinho	3154457
7608	2584	Riacho dos Machados	3154507
7609	2584	Ribeirao das Neves	3154606
7610	2584	Ribeirao Vermelho	3154705
7611	2584	Rio Acima	3154804
7612	2584	Rio Casca	3154903
7613	2584	Rio Doce	3155009
7614	2584	Rio do Prado	3155108
7615	2584	Rio Espera	3155207
7616	2584	Rio Manso	3155306
7617	2584	Rio Novo	3155405
7618	2584	Rio Paranaiba	3155504
7619	2584	Rio Pardo de Minas	3155603
7620	2584	Rio Piracicaba	3155702
7621	2584	Rio Pomba	3155801
7622	2584	Rio Preto	3155900
7623	2584	Rio Vermelho	3156007
7624	2584	Ritapolis	3156106
7625	2584	Rochedo de Minas	3156205
7626	2584	Rodeiro	3156304
7627	2584	Romaria	3156403
7628	2584	Rosario da Limeira	3156452
7629	2584	Rubelita	3156502
7630	2584	Rubim	3156601
7631	2584	Sabara	3156700
7632	2584	Sabinopolis	3156809
7633	2584	Sacramento	3156908
7634	2584	Salinas	3157005
7635	2584	Salto da Divisa	3157104
7636	2584	Santa Barbara	3157203
7637	2584	Santa Barbara do Leste	3157252
7638	2584	Santa Barbara do Monte Verde	3157278
7639	2584	Santa Barbara do Tugurio	3157302
7640	2584	Santa Cruz de Minas	3157336
7641	2584	Santa Cruz de Salinas	3157377
7642	2584	Santa Cruz do Escalvado	3157401
7643	2584	Santa Efigenia de Minas	3157500
7644	2584	Santa Fe de Minas	3157609
7645	2584	Santa Helena de Minas	3157658
7646	2584	Santa Juliana	3157708
7647	2584	Santa Luzia	3157807
7648	2584	Santa Margarida	3157906
7649	2584	Santa Maria de Itabira	3158003
7650	2584	Santa Maria do Salto	3158102
7651	2584	Santa Maria do Suacui	3158201
7652	2584	Santana da Vargem	3158300
7653	2584	Santana de Cataguases	3158409
7654	2584	Santana de Pirapama	3158508
7655	2584	Santana do Deserto	3158607
7656	2584	Santana do Garambeu	3158706
7657	2584	Santana do Jacare	3158805
7658	2584	Santana do Manhuacu	3158904
7659	2584	Santana do Paraiso	3158953
7660	2584	Santana do Riacho	3159001
7661	2584	Santana dos Montes	3159100
7662	2584	Santa Rita de Caldas	3159209
7663	2584	Santa Rita de Jacutinga	3159308
7664	2584	Santa Rita de Minas	3159357
7665	2584	Santa Rita de Ibitipoca	3159407
7666	2584	Santa Rita do Itueto	3159506
7667	2584	Santa Rita do Sapucai	3159605
7668	2584	Santa Rosa da Serra	3159704
7669	2584	Santa Vitoria	3159803
7670	2584	Santo Antonio do Amparo	3159902
7671	2584	Santo Antonio do Aventureiro	3160009
7672	2584	Santo Antonio do Grama	3160108
7673	2584	Santo Antonio do Itambe	3160207
7674	2584	Santo Antonio do Jacinto	3160306
7675	2584	Santo Antonio do Monte	3160405
7676	2584	Santo Antonio do Retiro	3160454
7677	2584	Santo Antonio do Rio Abaixo	3160504
7678	2584	Santo Hipolito	3160603
7679	2584	Santos Dumont	3160702
7680	2584	Sao Bento Abade	3160801
7681	2584	Sao Bras do Suacui	3160900
7682	2584	Sao Domingos das Dores	3160959
7683	2584	Sao Domingos do Prata	3161007
7684	2584	Sao Felix de Minas	3161056
7685	2584	Sao Francisco	3161106
7686	2584	Sao Francisco de Paula	3161205
7687	2584	Sao Francisco de Sales	3161304
7688	2584	Sao Francisco do Gloria	3161403
7689	2584	Sao Geraldo	3161502
7690	2584	Sao Geraldo da Piedade	3161601
7691	2584	Sao Geraldo do Baixio	3161650
7692	2584	Sao Goncalo do Abaete	3161700
7693	2584	Sao Goncalo do Para	3161809
7694	2584	Sao Goncalo do Rio Abaixo	3161908
7695	2584	Sao Goncalo do Sapucai	3162005
7696	2584	Sao Gotardo	3162104
7697	2584	Sao Joao Batista do Gloria	3162203
7698	2584	Sao Joao da Lagoa	3162252
7699	2584	Sao Joao da Mata	3162302
7700	2584	Sao Joao da Ponte	3162401
7701	2584	Sao Joao das Missoes	3162450
7702	2584	Sao Joao del Rei	3162500
7703	2584	Sao Joao do Manhuacu	3162559
7704	2584	Sao Joao do Manteninha	3162575
7705	2584	Sao Joao do Oriente	3162609
7706	2584	Sao Joao do Pacui	3162658
7707	2584	Sao Joao do Paraiso	3162708
7708	2584	Sao Joao Evangelista	3162807
7709	2584	Sao Joao Nepomuceno	3162906
7710	2584	Sao Joaquim de Bicas	3162922
7711	2584	Sao Jose da Barra	3162948
7712	2584	Sao Jose da Lapa	3162955
7713	2584	Sao Jose da Safira	3163003
7714	2584	Sao Jose da Varginha	3163102
7715	2584	Sao Jose do Alegre	3163201
7716	2584	Sao Jose do Divino	3163300
7717	2584	Sao Jose do Goiabal	3163409
7718	2584	Sao Jose do Jacuri	3163508
7719	2584	Sao Jose do Mantimento	3163607
7720	2584	Sao Lourenco	3163706
7721	2584	Sao Miguel do Anta	3163805
7722	2584	Sao Pedro da Uniao	3163904
7723	2584	Sao Pedro dos Ferros	3164001
7724	2584	Sao Pedro do Suacui	3164100
7725	2584	Sao Romao	3164209
7726	2584	Sao Roque de Minas	3164308
7727	2584	Sao Sebastiao da Bela Vista	3164407
7728	2584	Sao Sebastiao da Vargem Alegre	3164431
7729	2584	Sao Sebastiao do Anta	3164472
7730	2584	Sao Sebastiao do Maranhao	3164506
7731	2584	Sao Sebastiao do Oeste	3164605
7732	2584	Sao Sebastiao do Paraiso	3164704
7733	2584	Sao Sebastiao do Rio Preto	3164803
7734	2584	Sao Sebastiao do Rio Verde	3164902
7735	2584	Sao Tiago	3165008
7736	2584	Sao Tomas de Aquino	3165107
7737	2584	Sao Thome das Letras	3165206
7738	2584	Sao Vicente de Minas	3165305
7739	2584	Sapucai-Mirim	3165404
7740	2584	Sardoa	3165503
7741	2584	Sarzedo	3165537
7742	2584	Setubinha	3165552
7743	2584	Sem-Peixe	3165560
7744	2584	Senador Amaral	3165578
7745	2584	Senador Cortes	3165602
7746	2584	Senador Firmino	3165701
7747	2584	Senador Jose Bento	3165800
7748	2584	Senador Modestino Goncalves	3165909
7749	2584	Senhora de Oliveira	3166006
7750	2584	Senhora do Porto	3166105
7751	2584	Senhora dos Remedios	3166204
7752	2584	Sericita	3166303
7753	2584	Seritinga	3166402
7754	2584	Serra Azul de Minas	3166501
7755	2584	Serra da Saudade	3166600
7756	2584	Serra dos Aimores	3166709
7757	2584	Serra do Salitre	3166808
7758	2584	Serrania	3166907
7759	2584	Serranopolis de Minas	3166956
7760	2584	Serranos	3167004
7761	2584	Serro	3167103
7762	2584	Sete Lagoas	3167202
7763	2584	Silveirania	3167301
7764	2584	Silvianopolis	3167400
7765	2584	Simao Pereira	3167509
7766	2584	Simonesia	3167608
7767	2584	Sobralia	3167707
7768	2584	Soledade de Minas	3167806
7769	2584	Tabuleiro	3167905
7770	2584	Taiobeiras	3168002
7771	2584	Taparuba	3168051
7772	2584	Tapira	3168101
7773	2584	Tapirai	3168200
7774	2584	Taquaracu de Minas	3168309
7775	2584	Tarumirim	3168408
7776	2584	Teixeiras	3168507
7777	2584	Teofilo Otoni	3168606
7778	2584	Timoteo	3168705
7779	2584	Tiradentes	3168804
7780	2584	Tiros	3168903
7781	2584	Tocantins	3169000
7782	2584	Tocos do Moji	3169059
7783	2584	Toledo	3169109
7784	2584	Tombos	3169208
7785	2584	Tres Coracoes	3169307
7786	2584	Tres Marias	3169356
7787	2584	Tres Pontas	3169406
7788	2584	Tumiritinga	3169505
7789	2584	Tupaciguara	3169604
7790	2584	Turmalina	3169703
7791	2584	Turvolandia	3169802
7792	2584	Uba	3169901
7793	2584	Ubai	3170008
7794	2584	Ubaporanga	3170057
7795	2584	Uberaba	3170107
7796	2584	Uberlandia	3170206
7797	2584	Umburatiba	3170305
7798	2584	Unai	3170404
7799	2584	Uniao de Minas	3170438
7800	2584	Uruana de Minas	3170479
7801	2584	Urucania	3170503
7802	2584	Urucuia	3170529
7803	2584	Vargem Alegre	3170578
7804	2584	Vargem Bonita	3170602
7805	2584	Vargem Grande do Rio Pardo	3170651
7806	2584	Varginha	3170701
7807	2584	Varjao de Minas	3170750
7808	2584	Varzea da Palma	3170800
7809	2584	Varzelandia	3170909
7810	2584	Vazante	3171006
7811	2584	Verdelandia	3171030
7812	2584	Veredinha	3171071
7813	2584	Verissimo	3171105
7814	2584	Vermelho Novo	3171154
7815	2584	Vespasiano	3171204
7816	2584	Vicosa	3171303
7817	2584	Vieiras	3171402
7818	2584	Mathias Lobato	3171501
7819	2584	Virgem da Lapa	3171600
7820	2584	Virginia	3171709
7821	2584	Virginopolis	3171808
7822	2584	Virgolandia	3171907
7823	2584	Visconde do Rio Branco	3172004
7824	2584	Volta Grande	3172103
7825	2584	Wenceslau Braz	3172202
7826	2579	Afonso Claudio	3200102
7827	2579	Aguia Branca	3200136
7828	2579	Agua Doce do Norte	3200169
7829	2579	Alegre	3200201
7830	2579	Alfredo Chaves	3200300
7831	2579	Alto Rio Novo	3200359
7832	2579	Anchieta	3200409
7833	2579	Apiaca	3200508
7834	2579	Aracruz	3200607
7835	2579	Atilio Vivacqua	3200706
7836	2579	Baixo Guandu	3200805
7837	2579	Barra de Sao Francisco	3200904
7838	2579	Boa Esperanca	3201001
7839	2579	Bom Jesus do Norte	3201100
7840	2579	Brejetuba	3201159
7841	2579	Cachoeiro de Itapemirim	3201209
7842	2579	Cariacica	3201308
7843	2579	Castelo	3201407
7844	2579	Colatina	3201506
7845	2579	Conceicao da Barra	3201605
7846	2579	Conceicao do Castelo	3201704
7847	2579	Divino de Sao Lourenco	3201803
7848	2579	Domingos Martins	3201902
7849	2579	Dores do Rio Preto	3202009
7850	2579	Ecoporanga	3202108
7851	2579	Fundao	3202207
7852	2579	GOVERNADOR LINDENBERG	3202256
7853	2579	Guacui	3202306
7854	2579	Guarapari	3202405
7855	2579	Ibatiba	3202454
7856	2579	Ibiracu	3202504
7857	2579	Ibitirama	3202553
7858	2579	Iconha	3202603
7859	2579	Irupi	3202652
7860	2579	Itaguacu	3202702
7861	2579	Itapemirim	3202801
7862	2579	Itarana	3202900
7863	2579	Iuna	3203007
7864	2579	Jaguare	3203056
7865	2579	Jeronimo Monteiro	3203106
7866	2579	Joao Neiva	3203130
7867	2579	Laranja da Terra	3203163
7868	2579	Linhares	3203205
7869	2579	Mantenopolis	3203304
7870	2579	Marataizes	3203320
7871	2579	Marechal Floriano	3203346
7872	2579	Marilandia	3203353
7873	2579	Mimoso do Sul	3203403
7874	2579	Montanha	3203502
7875	2579	Mucurici	3203601
7876	2579	Muniz Freire	3203700
7877	2579	Muqui	3203809
7878	2579	Nova Venecia	3203908
7879	2579	Pancas	3204005
7880	2579	Pedro Canario	3204054
7881	2579	Pinheiros	3204104
7882	2579	Piuma	3204203
7883	2579	Ponto Belo	3204252
7884	2579	Presidente Kennedy	3204302
7885	2579	Rio Bananal	3204351
7886	2579	Rio Novo do Sul	3204401
7887	2579	Santa Leopoldina	3204500
7888	2579	Santa Maria de Jetiba	3204559
7889	2579	Santa Teresa	3204609
7890	2579	Sao Domingos do Norte	3204658
7891	2579	Sao Gabriel da Palha	3204708
7892	2579	Sao Jose do Calcado	3204807
7893	2579	Sao Mateus	3204906
7894	2579	Sao Roque do Canaa	3204955
7895	2579	Serra	3205002
7896	2579	Sooretama	3205010
7897	2579	Vargem Alta	3205036
7898	2579	Venda Nova do Imigrante	3205069
7899	2579	Viana	3205101
7900	2579	Vila Pavao	3205150
7901	2579	Vila Valerio	3205176
7902	2579	Vila Velha	3205200
7903	2579	Vitoria	3205309
7904	2590	Angra dos Reis	3300100
7905	2590	Aperibe	3300159
7906	2590	Araruama	3300209
7907	2590	Areal	3300225
7908	2590	Armacao de Buzios	3300233
7909	2590	Arraial do Cabo	3300258
7910	2590	Barra do Pirai	3300308
7911	2590	Barra Mansa	3300407
7912	2590	Belford Roxo	3300456
7913	2590	Bom Jardim	3300506
7914	2590	Bom Jesus do Itabapoana	3300605
7915	2590	Cabo Frio	3300704
7916	2590	Cachoeiras de Macacu	3300803
7917	2590	Cambuci	3300902
7918	2590	Carapebus	3300936
7919	2590	Comendador Levy Gasparian	3300951
7920	2590	Campos dos Goytacazes	3301009
7921	2590	Cantagalo	3301108
7922	2590	Cardoso Moreira	3301157
7923	2590	Carmo	3301207
7924	2590	Casimiro de Abreu	3301306
7925	2590	Conceicao de Macabu	3301405
7926	2590	Cordeiro	3301504
7927	2590	Duas Barras	3301603
7928	2590	Duque de Caxias	3301702
7929	2590	Engenheiro Paulo de Frontin	3301801
7930	2590	Guapimirim	3301850
7931	2590	Iguaba Grande	3301876
7932	2590	Itaborai	3301900
7933	2590	Itaguai	3302007
7934	2590	Italva	3302056
7935	2590	Itaocara	3302106
7936	2590	Itaperuna	3302205
7937	2590	Itatiaia	3302254
7938	2590	Japeri	3302270
7939	2590	Laje do Muriae	3302304
7940	2590	Macae	3302403
7941	2590	Macuco	3302452
7942	2590	Mage	3302502
7943	2590	Mangaratiba	3302601
7944	2590	Marica	3302700
7945	2590	Mendes	3302809
7946	2590	MESQUITA	3302858
7947	2590	Miguel Pereira	3302908
7948	2590	Miracema	3303005
7949	2590	Natividade	3303104
7950	2590	Nilopolis	3303203
7951	2590	Niteroi	3303302
7952	2590	Nova Friburgo	3303401
7953	2590	Nova Iguacu	3303500
7954	2590	Paracambi	3303609
7955	2590	Paraiba do Sul	3303708
7956	2590	Parati	3303807
7957	2590	Paty do Alferes	3303856
7958	2590	Petropolis	3303906
7959	2590	Pinheiral	3303955
7960	2590	Pirai	3304003
7961	2590	Porciuncula	3304102
7962	2590	Porto Real	3304110
7963	2590	Quatis	3304128
7964	2590	Queimados	3304144
7965	2590	Quissama	3304151
7966	2590	Resende	3304201
7967	2590	Rio Bonito	3304300
7968	2590	Rio Claro	3304409
7969	2590	Rio das Flores	3304508
7970	2590	Rio das Ostras	3304524
7971	2590	Rio de Janeiro	3304557
7972	2590	Santa Maria Madalena	3304607
7973	2590	Santo Antonio de Padua	3304706
7974	2590	Sao Francisco de Itabapoana	3304755
7975	2590	Sao Fidelis	3304805
7976	2590	Sao Goncalo	3304904
7977	2590	Sao Joao da Barra	3305000
7978	2590	Sao Joao de Meriti	3305109
7979	2590	Sao Jose de Uba	3305133
7980	2590	Sao Jose do Vale do Rio Preto	3305158
7981	2590	Sao Pedro da Aldeia	3305208
7982	2590	Sao Sebastiao do Alto	3305307
7983	2590	Sapucaia	3305406
7984	2590	Saquarema	3305505
7985	2590	Seropedica	3305554
7986	2590	Silva Jardim	3305604
7987	2590	Sumidouro	3305703
7988	2590	Tangua	3305752
7989	2590	Teresopolis	3305802
7990	2590	Trajano de Morais	3305901
7991	2590	Tres Rios	3306008
7992	2590	Valenca	3306107
7993	2590	Varre-Sai	3306156
7994	2590	Vassouras	3306206
7995	2590	Volta Redonda	3306305
7996	2596	Adamantina	3500105
7997	2596	Adolfo	3500204
7998	2596	Aguai	3500303
7999	2596	Aguas da Prata	3500402
8000	2596	Aguas de Lindoia	3500501
8001	2596	Aguas de Santa Barbara	3500550
8002	2596	Aguas de Sao Pedro	3500600
8003	2596	Agudos	3500709
8004	2596	Alambari	3500758
8005	2596	Alfredo Marcondes	3500808
8006	2596	Altair	3500907
8007	2596	Altinopolis	3501004
8008	2596	Alto Alegre	3501103
8009	2596	Aluminio	3501152
8010	2596	Alvares Florence	3501202
8011	2596	Alvares Machado	3501301
8012	2596	Alvaro de Carvalho	3501400
8013	2596	Alvinlandia	3501509
8014	2596	Americana	3501608
8015	2596	Americo Brasiliense	3501707
8016	2596	Americo de Campos	3501806
8017	2596	Amparo	3501905
8018	2596	Analandia	3502002
8019	2596	Andradina	3502101
8020	2596	Angatuba	3502200
8021	2596	Anhembi	3502309
8022	2596	Anhumas	3502408
8023	2596	Aparecida	3502507
8024	2596	Aparecida d'Oeste	3502606
8025	2596	Apiai	3502705
8026	2596	Aracariguama	3502754
8027	2596	Aracatuba	3502804
8028	2596	Aracoiaba da Serra	3502903
8029	2596	Aramina	3503000
8030	2596	Arandu	3503109
8031	2596	Arapei	3503158
8032	2596	Araraquara	3503208
8033	2596	Araras	3503307
8034	2596	Arco-Iris	3503356
8035	2596	Arealva	3503406
8036	2596	Areias	3503505
8037	2596	Areiopolis	3503604
8038	2596	Ariranha	3503703
8039	2596	Artur Nogueira	3503802
8040	2596	Aruja	3503901
8041	2596	Aspasia	3503950
8042	2596	Assis	3504008
8043	2596	Atibaia	3504107
8044	2596	Auriflama	3504206
8045	2596	Avai	3504305
8046	2596	Avanhandava	3504404
8047	2596	Avare	3504503
8048	2596	Bady Bassitt	3504602
8049	2596	Balbinos	3504701
8050	2596	Balsamo	3504800
8051	2596	Bananal	3504909
8052	2596	Barao de Antonina	3505005
8053	2596	Barbosa	3505104
8054	2596	Bariri	3505203
8055	2596	Barra Bonita	3505302
8056	2596	Barra do Chapeu	3505351
8057	2596	Barra do Turvo	3505401
8058	2596	Barretos	3505500
8059	2596	Barrinha	3505609
8060	2596	Barueri	3505708
8061	2596	Bastos	3505807
8062	2596	Batatais	3505906
8063	2596	Bauru	3506003
8064	2596	Bebedouro	3506102
8065	2596	Bento de Abreu	3506201
8066	2596	Bernardino de Campos	3506300
8067	2596	Bertioga	3506359
8068	2596	Bilac	3506409
8069	2596	Birigui	3506508
8070	2596	Biritiba-Mirim	3506607
8071	2596	Boa Esperanca do Sul	3506706
8072	2596	Bocaina	3506805
8073	2596	Bofete	3506904
8074	2596	Boituva	3507001
8075	2596	Bom Jesus dos Perdoes	3507100
8076	2596	Bom Sucesso de Itarare	3507159
8077	2596	Bora	3507209
8078	2596	Boraceia	3507308
8079	2596	Borborema	3507407
8080	2596	Borebi	3507456
8081	2596	Botucatu	3507506
8082	2596	Braganca Paulista	3507605
8083	2596	Brauna	3507704
8084	2596	Brejo Alegre	3507753
8085	2596	Brodosqui	3507803
8086	2596	Brotas	3507902
8087	2596	Buri	3508009
8088	2596	Buritama	3508108
8089	2596	Buritizal	3508207
8090	2596	Cabralia Paulista	3508306
8091	2596	Cabreuva	3508405
8092	2596	Cacapava	3508504
8093	2596	Cachoeira Paulista	3508603
8094	2596	Caconde	3508702
8095	2596	Cafelandia	3508801
8096	2596	Caiabu	3508900
8097	2596	Caieiras	3509007
8098	2596	Caiua	3509106
8099	2596	Cajamar	3509205
8100	2596	Cajati	3509254
8101	2596	Cajobi	3509304
8102	2596	Cajuru	3509403
8103	2596	Campina do Monte Alegre	3509452
8104	2596	Campinas	3509502
8105	2596	Campo Limpo Paulista	3509601
8106	2596	Campos do Jordao	3509700
8107	2596	Campos Novos Paulista	3509809
8108	2596	Cananeia	3509908
8109	2596	Canas	3509957
8110	2596	Candido Mota	3510005
8111	2596	Candido Rodrigues	3510104
8112	2596	Canitar	3510153
8113	2596	Capao Bonito	3510203
8114	2596	Capela do Alto	3510302
8115	2596	Capivari	3510401
8116	2596	Caraguatatuba	3510500
8117	2596	Carapicuiba	3510609
8118	2596	Cardoso	3510708
8119	2596	Casa Branca	3510807
8120	2596	Cassia dos Coqueiros	3510906
8121	2596	Castilho	3511003
8122	2596	Catanduva	3511102
8123	2596	Catigua	3511201
8124	2596	Cedral	3511300
8125	2596	Cerqueira Cesar	3511409
8126	2596	Cerquilho	3511508
8127	2596	Cesario Lange	3511607
8128	2596	Charqueada	3511706
8129	2596	Clementina	3511904
8130	2596	Colina	3512001
8131	2596	Colombia	3512100
8132	2596	Conchal	3512209
8133	2596	Conchas	3512308
8134	2596	Cordeiropolis	3512407
8135	2596	Coroados	3512506
8136	2596	Coronel Macedo	3512605
8137	2596	Corumbatai	3512704
8138	2596	Cosmopolis	3512803
8139	2596	Cosmorama	3512902
8140	2596	Cotia	3513009
8141	2596	Cravinhos	3513108
8142	2596	Cristais Paulista	3513207
8143	2596	Cruzalia	3513306
8144	2596	Cruzeiro	3513405
8145	2596	Cubatao	3513504
8146	2596	Cunha	3513603
8147	2596	Descalvado	3513702
8148	2596	Diadema	3513801
8149	2596	Dirce Reis	3513850
8150	2596	Divinolandia	3513900
8151	2596	Dobrada	3514007
8152	2596	Dois Corregos	3514106
8153	2596	Dolcinopolis	3514205
8154	2596	Dourado	3514304
8155	2596	Dracena	3514403
8156	2596	Duartina	3514502
8157	2596	Dumont	3514601
8158	2596	Echapora	3514700
8159	2596	Eldorado	3514809
8160	2596	Elias Fausto	3514908
8161	2596	Elisiario	3514924
8162	2596	Embauba	3514957
8163	2596	Embu	3515004
8164	2596	Embu-Guacu	3515103
8165	2596	Emilianopolis	3515129
8166	2596	Engenheiro Coelho	3515152
8167	2596	Espirito Santo do Pinhal	3515186
8168	2596	Espirito Santo do Turvo	3515194
8169	2596	Estrela d'Oeste	3515202
8170	2596	Estrela do Norte	3515301
8171	2596	Euclides da Cunha Paulista	3515350
8172	2596	Fartura	3515400
8173	2596	Fernandopolis	3515509
8174	2596	Fernando Prestes	3515608
8175	2596	Fernao	3515657
8176	2596	Ferraz de Vasconcelos	3515707
8177	2596	Flora Rica	3515806
8178	2596	Floreal	3515905
8179	2596	Florida Paulista	3516002
8180	2596	Florinia	3516101
8181	2596	Franca	3516200
8182	2596	Francisco Morato	3516309
8183	2596	Franco da Rocha	3516408
8184	2596	Gabriel Monteiro	3516507
8185	2596	Galia	3516606
8186	2596	Garca	3516705
8187	2596	Gastao Vidigal	3516804
8188	2596	Gaviao Peixoto	3516853
8189	2596	General Salgado	3516903
8190	2596	Getulina	3517000
8191	2596	Glicerio	3517109
8192	2596	Guaicara	3517208
8193	2596	Guaimbe	3517307
8194	2596	Guaira	3517406
8195	2596	Guapiacu	3517505
8196	2596	Guapiara	3517604
8197	2596	Guara	3517703
8198	2596	Guaracai	3517802
8199	2596	Guaraci	3517901
8200	2596	Guarani d'Oeste	3518008
8201	2596	Guaranta	3518107
8202	2596	Guararapes	3518206
8203	2596	Guararema	3518305
8204	2596	Guaratingueta	3518404
8205	2596	Guarei	3518503
8206	2596	Guariba	3518602
8207	2596	Guaruja	3518701
8208	2596	Guarulhos	3518800
8209	2596	Guatapara	3518859
8210	2596	Guzolandia	3518909
8211	2596	Herculandia	3519006
8212	2596	Holambra	3519055
8213	2596	Hortolandia	3519071
8214	2596	Iacanga	3519105
8215	2596	Iacri	3519204
8216	2596	Iaras	3519253
8217	2596	Ibate	3519303
8218	2596	Ibira	3519402
8219	2596	Ibirarema	3519501
8220	2596	Ibitinga	3519600
8221	2596	Ibiuna	3519709
8222	2596	Icem	3519808
8223	2596	Iepe	3519907
8224	2596	Igaracu do Tiete	3520004
8225	2596	Igarapava	3520103
8226	2596	Igarata	3520202
8227	2596	Iguape	3520301
8228	2596	Ilhabela	3520400
8229	2596	Ilha Comprida	3520426
8230	2596	Ilha Solteira	3520442
8231	2596	Indaiatuba	3520509
8232	2596	Indiana	3520608
8233	2596	Indiapora	3520707
8234	2596	Inubia Paulista	3520806
8235	2596	Ipaucu	3520905
8236	2596	Ipero	3521002
8237	2596	Ipeuna	3521101
8238	2596	Ipigua	3521150
8239	2596	Iporanga	3521200
8240	2596	Ipua	3521309
8241	2596	Iracemapolis	3521408
8242	2596	Irapua	3521507
8243	2596	Irapuru	3521606
8244	2596	Itabera	3521705
8245	2596	Itai	3521804
8246	2596	Itajobi	3521903
8247	2596	Itaju	3522000
8248	2596	Itanhaem	3522109
8249	2596	Itaoca	3522158
8250	2596	Itapecerica da Serra	3522208
8251	2596	Itapetininga	3522307
8252	2596	Itapeva	3522406
8253	2596	Itapevi	3522505
8254	2596	Itapira	3522604
8255	2596	Itapirapua Paulista	3522653
8256	2596	Itapolis	3522703
8257	2596	Itaporanga	3522802
8258	2596	Itapui	3522901
8259	2596	Itapura	3523008
8260	2596	Itaquaquecetuba	3523107
8261	2596	Itarare	3523206
8262	2596	Itariri	3523305
8263	2596	Itatiba	3523404
8264	2596	Itatinga	3523503
8265	2596	Itirapina	3523602
8266	2596	Itirapua	3523701
8267	2596	Itobi	3523800
8268	2596	Itu	3523909
8269	2596	Itupeva	3524006
8270	2596	Ituverava	3524105
8271	2596	Jaborandi	3524204
8272	2596	Jaboticabal	3524303
8273	2596	Jacarei	3524402
8274	2596	Jaci	3524501
8275	2596	Jacupiranga	3524600
8276	2596	Jaguariuna	3524709
8277	2596	Jales	3524808
8278	2596	Jambeiro	3524907
8279	2596	Jandira	3525003
8280	2596	Jardinopolis	3525102
8281	2596	Jarinu	3525201
8282	2596	Jau	3525300
8283	2596	Jeriquara	3525409
8284	2596	Joanopolis	3525508
8285	2596	Joao Ramalho	3525607
8286	2596	Jose Bonifacio	3525706
8287	2596	Julio Mesquita	3525805
8288	2596	Jumirim	3525854
8289	2596	Jundiai	3525904
8290	2596	Junqueiropolis	3526001
8291	2596	Juquia	3526100
8292	2596	Juquitiba	3526209
8293	2596	Lagoinha	3526308
8294	2596	Laranjal Paulista	3526407
8295	2596	Lavinia	3526506
8296	2596	Lavrinhas	3526605
8297	2596	Leme	3526704
8298	2596	Lencois Paulista	3526803
8299	2596	Limeira	3526902
8300	2596	Lindoia	3527009
8301	2596	Lins	3527108
8302	2596	Lorena	3527207
8303	2596	Lourdes	3527256
8304	2596	Louveira	3527306
8305	2596	Lucelia	3527405
8306	2596	Lucianopolis	3527504
8307	2596	Luis Antonio	3527603
8308	2596	Luiziania	3527702
8309	2596	Lupercio	3527801
8310	2596	Lutecia	3527900
8311	2596	Macatuba	3528007
8312	2596	Macaubal	3528106
8313	2596	Macedonia	3528205
8314	2596	Magda	3528304
8315	2596	Mairinque	3528403
8316	2596	Mairipora	3528502
8317	2596	Manduri	3528601
8318	2596	Maraba Paulista	3528700
8319	2596	Maracai	3528809
8320	2596	Marapoama	3528858
8321	2596	Mariapolis	3528908
8322	2596	Marilia	3529005
8323	2596	Marinopolis	3529104
8324	2596	Martinopolis	3529203
8325	2596	Matao	3529302
8326	2596	Maua	3529401
8327	2596	Mendonca	3529500
8328	2596	Meridiano	3529609
8329	2596	Mesopolis	3529658
8330	2596	Miguelopolis	3529708
8331	2596	Mineiros do Tiete	3529807
8332	2596	Miracatu	3529906
8333	2596	Mira Estrela	3530003
8334	2596	Mirandopolis	3530102
8335	2596	Mirante do Paranapanema	3530201
8336	2596	Mirassol	3530300
8337	2596	Mirassolandia	3530409
8338	2596	Mococa	3530508
8339	2596	Moji das Cruzes	3530607
8340	2596	Moji-Guacu	3530706
8341	2596	Moji-Mirim	3530805
8342	2596	Mombuca	3530904
8343	2596	Moncoes	3531001
8344	2596	Mongagua	3531100
8345	2596	Monte Alegre do Sul	3531209
8346	2596	Monte Alto	3531308
8347	2596	Monte Aprazivel	3531407
8348	2596	Monte Azul Paulista	3531506
8349	2596	Monte Castelo	3531605
8350	2596	Monteiro Lobato	3531704
8351	2596	Monte Mor	3531803
8352	2596	Morro Agudo	3531902
8353	2596	Morungaba	3532009
8354	2596	Motuca	3532058
8355	2596	Murutinga do Sul	3532108
8356	2596	Nantes	3532157
8357	2596	Narandiba	3532207
8358	2596	Natividade da Serra	3532306
8359	2596	Nazare Paulista	3532405
8360	2596	Neves Paulista	3532504
8361	2596	Nhandeara	3532603
8362	2596	Nipoa	3532702
8363	2596	Nova Alianca	3532801
8364	2596	Nova Campina	3532827
8365	2596	Nova Canaa Paulista	3532843
8366	2596	Nova Castilho	3532868
8367	2596	Nova Europa	3532900
8368	2596	Nova Granada	3533007
8369	2596	Nova Guataporanga	3533106
8370	2596	Nova Independencia	3533205
8371	2596	Novais	3533254
8372	2596	Nova Luzitania	3533304
8373	2596	Nova Odessa	3533403
8374	2596	Novo Horizonte	3533502
8375	2596	Nuporanga	3533601
8376	2596	Ocaucu	3533700
8377	2596	Oleo	3533809
8378	2596	Olimpia	3533908
8379	2596	Onda Verde	3534005
8380	2596	Oriente	3534104
8381	2596	Orindiuva	3534203
8382	2596	Orlandia	3534302
8383	2596	Osasco	3534401
8384	2596	Oscar Bressane	3534500
8385	2596	Osvaldo Cruz	3534609
8386	2596	Ourinhos	3534708
8387	2596	Ouroeste	3534757
8388	2596	Ouro Verde	3534807
8389	2596	Pacaembu	3534906
8390	2596	Palestina	3535002
8391	2596	Palmares Paulista	3535101
8392	2596	Palmeira d'Oeste	3535200
8393	2596	Palmital	3535309
8394	2596	Panorama	3535408
8395	2596	Paraguacu Paulista	3535507
8396	2596	Paraibuna	3535606
8397	2596	Paraiso	3535705
8398	2596	Paranapanema	3535804
8399	2596	Paranapua	3535903
8400	2596	Parapua	3536000
8401	2596	Pardinho	3536109
8402	2596	Pariquera-Acu	3536208
8403	2596	Parisi	3536257
8404	2596	Patrocinio Paulista	3536307
8405	2596	Pauliceia	3536406
8406	2596	Paulinia	3536505
8407	2596	Paulistania	3536570
8408	2596	Paulo de Faria	3536604
8409	2596	Pederneiras	3536703
8410	2596	Pedra Bela	3536802
8411	2596	Pedranopolis	3536901
8412	2596	Pedregulho	3537008
8413	2596	Pedreira	3537107
8414	2596	Pedrinhas Paulista	3537156
8415	2596	Pedro de Toledo	3537206
8416	2596	Penapolis	3537305
8417	2596	Pereira Barreto	3537404
8418	2596	Pereiras	3537503
8419	2596	Peruibe	3537602
8420	2596	Piacatu	3537701
8421	2596	Piedade	3537800
8422	2596	Pilar do Sul	3537909
8423	2596	Pindamonhangaba	3538006
8424	2596	Pindorama	3538105
8425	2596	Pinhalzinho	3538204
8426	2596	Piquerobi	3538303
8427	2596	Piquete	3538501
8428	2596	Piracaia	3538600
8429	2596	Piracicaba	3538709
8430	2596	Piraju	3538808
8431	2596	Pirajui	3538907
8432	2596	Pirangi	3539004
8433	2596	Pirapora do Bom Jesus	3539103
8434	2596	Pirapozinho	3539202
8435	2596	Pirassununga	3539301
8436	2596	Piratininga	3539400
8437	2596	Pitangueiras	3539509
8438	2596	Planalto	3539608
8439	2596	Platina	3539707
8440	2596	Poa	3539806
8441	2596	Poloni	3539905
8442	2596	Pompeia	3540002
8443	2596	Pongai	3540101
8444	2596	Pontal	3540200
8445	2596	Pontalinda	3540259
8446	2596	Pontes Gestal	3540309
8447	2596	Populina	3540408
8448	2596	Porangaba	3540507
8449	2596	Porto Feliz	3540606
8450	2596	Porto Ferreira	3540705
8451	2596	Potim	3540754
8452	2596	Potirendaba	3540804
8453	2596	Pracinha	3540853
8454	2596	Pradopolis	3540903
8455	2596	Praia Grande	3541000
8456	2596	Pratania	3541059
8457	2596	Presidente Alves	3541109
8458	2596	Presidente Bernardes	3541208
8459	2596	Presidente Epitacio	3541307
8460	2596	Presidente Prudente	3541406
8461	2596	Presidente Venceslau	3541505
8462	2596	Promissao	3541604
8463	2596	Quadra	3541653
8464	2596	Quata	3541703
8465	2596	Queiroz	3541802
8466	2596	Queluz	3541901
8467	2596	Quintana	3542008
8468	2596	Rafard	3542107
8469	2596	Rancharia	3542206
8470	2596	Redencao da Serra	3542305
8471	2596	Regente Feijo	3542404
8472	2596	Reginopolis	3542503
8473	2596	Registro	3542602
8474	2596	Restinga	3542701
8475	2596	Ribeira	3542800
8476	2596	Ribeirao Bonito	3542909
8477	2596	Ribeirao Branco	3543006
8478	2596	Ribeirao Corrente	3543105
8479	2596	Ribeirao do Sul	3543204
8480	2596	Ribeirao dos Indios	3543238
8481	2596	Ribeirao Grande	3543253
8482	2596	Ribeirao Pires	3543303
8483	2596	Ribeirao Preto	3543402
8484	2596	Riversul	3543501
8485	2596	Rifaina	3543600
8486	2596	Rincao	3543709
8487	2596	Rinopolis	3543808
8488	2596	Rio Claro	3543907
8489	2596	Rio das Pedras	3544004
8490	2596	Rio Grande da Serra	3544103
8491	2596	Riolandia	3544202
8492	2596	Rosana	3544251
8493	2596	Roseira	3544301
8494	2596	Rubiacea	3544400
8495	2596	Rubineia	3544509
8496	2596	Sabino	3544608
8497	2596	Sagres	3544707
8498	2596	Sales	3544806
8499	2596	Sales Oliveira	3544905
8500	2596	Salesopolis	3545001
8501	2596	Salmourao	3545100
8502	2596	Saltinho	3545159
8503	2596	Salto	3545209
8504	2596	Salto de Pirapora	3545308
8505	2596	Salto Grande	3545407
8506	2596	Sandovalina	3545506
8507	2596	Santa Adelia	3545605
8508	2596	Santa Albertina	3545704
8509	2596	Santa Barbara d'Oeste	3545803
8510	2596	Santa Branca	3546009
8511	2596	Santa Clara d'Oeste	3546108
8512	2596	Santa Cruz da Conceicao	3546207
8513	2596	Santa Cruz da Esperanca	3546256
8514	2596	Santa Cruz das Palmeiras	3546306
8515	2596	Santa Cruz do Rio Pardo	3546405
8516	2596	Santa Ernestina	3546504
8517	2596	Santa Fe do Sul	3546603
8518	2596	Santa Gertrudes	3546702
8519	2596	Santa Isabel	3546801
8520	2596	Santa Lucia	3546900
8521	2596	Santa Maria da Serra	3547007
8522	2596	Santa Mercedes	3547106
8523	2596	Santana da Ponte Pensa	3547205
8524	2596	Santana de Parnaiba	3547304
8525	2596	Santa Rita d'Oeste	3547403
8526	2596	Santa Rita do Passa Quatro	3547502
8527	2596	Santa Rosa de Viterbo	3547601
8528	2596	Santa Salete	3547650
8529	2596	Santo Anastacio	3547700
8530	2596	Santo Andre	3547809
8531	2596	Santo Antonio da Alegria	3547908
8532	2596	Santo Antonio de Posse	3548005
8671	2587	Barracao	4102604
8533	2596	Santo Antonio do Aracangua	3548054
8534	2596	Santo Antonio do Jardim	3548104
8535	2596	Santo Antonio do Pinhal	3548203
8536	2596	Santo Expedito	3548302
8537	2596	Santopolis do Aguapei	3548401
8538	2596	Santos	3548500
8539	2596	Sao Bento do Sapucai	3548609
8540	2596	Sao Bernardo do Campo	3548708
8541	2596	Sao Caetano do Sul	3548807
8542	2596	Sao Carlos	3548906
8543	2596	Sao Francisco	3549003
8544	2596	Sao Joao da Boa Vista	3549102
8545	2596	Sao Joao das Duas Pontes	3549201
8546	2596	Sao Joao de Iracema	3549250
8547	2596	Sao Joao do Pau d'Alho	3549300
8548	2596	Sao Joaquim da Barra	3549409
8549	2596	Sao Jose da Bela Vista	3549508
8550	2596	Sao Jose do Barreiro	3549607
8551	2596	Sao Jose do Rio Pardo	3549706
8552	2596	Sao Jose do Rio Preto	3549805
8553	2596	Sao Jose dos Campos	3549904
8554	2596	Sao Lourenco da Serra	3549953
8555	2596	Sao Luis do Paraitinga	3550001
8556	2596	Sao Manuel	3550100
8557	2596	Sao Miguel Arcanjo	3550209
8558	2596	Sao Paulo	3550308
8559	2596	Sao Pedro	3550407
8560	2596	Sao Pedro do Turvo	3550506
8561	2596	Sao Roque	3550605
8562	2596	Sao Sebastiao	3550704
8563	2596	Sao Sebastiao da Grama	3550803
8564	2596	Sao Simao	3550902
8565	2596	Sao Vicente	3551009
8566	2596	Sarapui	3551108
8567	2596	Sarutaia	3551207
8568	2596	Sebastianopolis do Sul	3551306
8569	2596	Serra Azul	3551405
8570	2596	Serrana	3551504
8571	2596	Serra Negra	3551603
8572	2596	Sertaozinho	3551702
8573	2596	Sete Barras	3551801
8574	2596	Severinia	3551900
8575	2596	Silveiras	3552007
8576	2596	Socorro	3552106
8577	2596	Sorocaba	3552205
8578	2596	Sud Mennucci	3552304
8579	2596	Sumare	3552403
8580	2596	Suzano	3552502
8581	2596	Suzanapolis	3552551
8582	2596	Tabapua	3552601
8583	2596	Tabatinga	3552700
8584	2596	Taboao da Serra	3552809
8585	2596	Taciba	3552908
8586	2596	Taguai	3553005
8587	2596	Taiacu	3553104
8588	2596	Taiuva	3553203
8589	2596	Tambau	3553302
8590	2596	Tanabi	3553401
8591	2596	Tapirai	3553500
8592	2596	Tapiratiba	3553609
8593	2596	Taquaral	3553658
8594	2596	Taquaritinga	3553708
8595	2596	Taquarituba	3553807
8596	2596	Taquarivai	3553856
8597	2596	Tarabai	3553906
8598	2596	Taruma	3553955
8599	2596	Tatui	3554003
8600	2596	Taubate	3554102
8601	2596	Tejupa	3554201
8602	2596	Teodoro Sampaio	3554300
8603	2596	Terra Roxa	3554409
8604	2596	Tiete	3554508
8605	2596	Timburi	3554607
8606	2596	Torre de Pedra	3554656
8607	2596	Torrinha	3554706
8608	2596	Trabiju	3554755
8609	2596	Tremembe	3554805
8610	2596	Tres Fronteiras	3554904
8611	2596	Tuiuti	3554953
8612	2596	Tupa	3555000
8613	2596	Tupi Paulista	3555109
8614	2596	Turiuba	3555208
8615	2596	Turmalina	3555307
8616	2596	Ubarana	3555356
8617	2596	Ubatuba	3555406
8618	2596	Ubirajara	3555505
8619	2596	Uchoa	3555604
8620	2596	Uniao Paulista	3555703
8621	2596	Urania	3555802
8622	2596	Uru	3555901
8623	2596	Urupes	3556008
8624	2596	Valentim Gentil	3556107
8625	2596	Valinhos	3556206
8626	2596	Valparaiso	3556305
8627	2596	Vargem	3556354
8628	2596	Vargem Grande do Sul	3556404
8629	2596	Vargem Grande Paulista	3556453
8630	2596	Varzea Paulista	3556503
8631	2596	Vera Cruz	3556602
8632	2596	Vinhedo	3556701
8633	2596	Viradouro	3556800
8634	2596	Vista Alegre do Alto	3556909
8635	2596	Vitoria Brasil	3556958
8636	2596	Votorantim	3557006
8637	2596	Votuporanga	3557105
8638	2596	Zacarias	3557154
8639	2596	Chavantes	3557204
8640	2596	Estiva Gerbi	3557303
8641	2587	Abatia	4100103
8642	2587	Adrianopolis	4100202
8643	2587	Agudos do Sul	4100301
8644	2587	Almirante Tamandare	4100400
8645	2587	Altamira do Parana	4100459
8646	2587	Altonia	4100509
8647	2587	Alto Parana	4100608
8648	2587	Alto Piquiri	4100707
8649	2587	Alvorada do Sul	4100806
8650	2587	Amapora	4100905
8651	2587	Ampere	4101002
8652	2587	Anahy	4101051
8653	2587	Andira	4101101
8654	2587	Angulo	4101150
8655	2587	Antonina	4101200
8656	2587	Antonio Olinto	4101309
8657	2587	Apucarana	4101408
8658	2587	Arapongas	4101507
8659	2587	Arapoti	4101606
8660	2587	Arapua	4101655
8661	2587	Araruna	4101705
8662	2587	Araucaria	4101804
8663	2587	Ariranha do Ivai	4101853
8664	2587	Assai	4101903
8665	2587	Assis Chateaubriand	4102000
8666	2587	Astorga	4102109
8667	2587	Atalaia	4102208
8668	2587	Balsa Nova	4102307
8669	2587	Bandeirantes	4102406
8670	2587	Barbosa Ferraz	4102505
8672	2587	Barra do Jacare	4102703
8673	2587	Bela Vista do Caroba	4102752
8674	2587	Bela Vista do Paraiso	4102802
8675	2587	Bituruna	4102901
8676	2587	Boa Esperanca	4103008
8677	2587	Boa Esperanca do Iguacu	4103024
8678	2587	Boa Ventura de Sao Roque	4103040
8679	2587	Boa Vista da Aparecida	4103057
8680	2587	Bocaiuva do Sul	4103107
8681	2587	Bom Jesus do Sul	4103156
8682	2587	Bom Sucesso	4103206
8683	2587	Bom Sucesso do Sul	4103222
8684	2587	Borrazopolis	4103305
8685	2587	Braganey	4103354
8686	2587	Brasilandia do Sul	4103370
8687	2587	Cafeara	4103404
8688	2587	Cafelandia	4103453
8689	2587	Cafezal do Sul	4103479
8690	2587	California	4103503
8691	2587	Cambara	4103602
8692	2587	Cambe	4103701
8693	2587	Cambira	4103800
8694	2587	Campina da Lagoa	4103909
8695	2587	Campina do Simao	4103958
8696	2587	Campina Grande do Sul	4104006
8697	2587	Campo Bonito	4104055
8698	2587	Campo do Tenente	4104105
8699	2587	Campo Largo	4104204
8700	2587	Campo Magro	4104253
8701	2587	Campo Mourao	4104303
8702	2587	Candido de Abreu	4104402
8703	2587	Candoi	4104428
8704	2587	Cantagalo	4104451
8705	2587	Capanema	4104501
8706	2587	Capitao Leonidas Marques	4104600
8707	2587	Carambei	4104659
8708	2587	Carlopolis	4104709
8709	2587	Cascavel	4104808
8710	2587	Castro	4104907
8711	2587	Catanduvas	4105003
8712	2587	Centenario do Sul	4105102
8713	2587	Cerro Azul	4105201
8714	2587	Ceu Azul	4105300
8715	2587	Chopinzinho	4105409
8716	2587	Cianorte	4105508
8717	2587	Cidade Gaucha	4105607
8718	2587	Clevelandia	4105706
8719	2587	Colombo	4105805
8720	2587	Colorado	4105904
8721	2587	Congonhinhas	4106001
8722	2587	Conselheiro Mairinck	4106100
8723	2587	Contenda	4106209
8724	2587	Corbelia	4106308
8725	2587	Cornelio Procopio	4106407
8726	2587	Coronel Domingos Soares	4106456
8727	2587	Coronel Vivida	4106506
8728	2587	Corumbatai do Sul	4106555
8729	2587	Cruzeiro do Iguacu	4106571
8730	2587	Cruzeiro do Oeste	4106605
8731	2587	Cruzeiro do Sul	4106704
8732	2587	Cruz Machado	4106803
8733	2587	Cruzmaltina	4106852
8734	2587	Curitiba	4106902
8735	2587	Curiuva	4107009
8736	2587	Diamante do Norte	4107108
8737	2587	Diamante do Sul	4107124
8738	2587	Diamante D'Oeste	4107157
8739	2587	Dois Vizinhos	4107207
8740	2587	Douradina	4107256
8741	2587	Doutor Camargo	4107306
8742	2587	Eneas Marques	4107405
8743	2587	Engenheiro Beltrao	4107504
8744	2587	Esperanca Nova	4107520
8745	2587	Entre Rios do Oeste	4107538
8746	2587	Espigao Alto do Iguacu	4107546
8747	2587	Farol	4107553
8748	2587	Faxinal	4107603
8749	2587	Fazenda Rio Grande	4107652
8750	2587	Fenix	4107702
8751	2587	Fernandes Pinheiro	4107736
8752	2587	Figueira	4107751
8753	2587	Florai	4107801
8754	2587	Flor da Serra do Sul	4107850
8755	2587	Floresta	4107900
8756	2587	Florestopolis	4108007
8757	2587	Florida	4108106
8758	2587	Formosa do Oeste	4108205
8759	2587	Foz do Iguacu	4108304
8760	2587	Francisco Alves	4108320
8761	2587	Francisco Beltrao	4108403
8762	2587	Foz do Jordao	4108452
8763	2587	General Carneiro	4108502
8764	2587	Godoy Moreira	4108551
8765	2587	Goioere	4108601
8766	2587	Goioxim	4108650
8767	2587	Grandes Rios	4108700
8768	2587	Guaira	4108809
8769	2587	Guairaca	4108908
8770	2587	Guamiranga	4108957
8771	2587	Guapirama	4109005
8772	2587	Guaporema	4109104
8773	2587	Guaraci	4109203
8774	2587	Guaraniacu	4109302
8775	2587	Guarapuava	4109401
8776	2587	Guaraquecaba	4109500
8777	2587	Guaratuba	4109609
8778	2587	Honorio Serpa	4109658
8779	2587	Ibaiti	4109708
8780	2587	Ibema	4109757
8781	2587	Ibipora	4109807
8782	2587	Icaraima	4109906
8783	2587	Iguaracu	4110003
8784	2587	Iguatu	4110052
8785	2587	Imbau	4110078
8786	2587	Imbituva	4110102
8787	2587	Inacio Martins	4110201
8788	2587	Inaja	4110300
8789	2587	Indianopolis	4110409
8790	2587	Ipiranga	4110508
8791	2587	Ipora	4110607
8792	2587	Iracema do Oeste	4110656
8793	2587	Irati	4110706
8794	2587	Iretama	4110805
8795	2587	Itaguaje	4110904
8796	2587	Itaipulandia	4110953
8797	2587	Itambaraca	4111001
8798	2587	Itambe	4111100
8799	2587	Itapejara d'Oeste	4111209
8800	2587	Itaperucu	4111258
8801	2587	Itauna do Sul	4111308
8802	2587	Ivai	4111407
8803	2587	Ivaipora	4111506
8804	2587	Ivate	4111555
8805	2587	Ivatuba	4111605
8806	2587	Jaboti	4111704
8807	2587	Jacarezinho	4111803
8808	2587	Jaguapita	4111902
8809	2587	Jaguariaiva	4112009
8810	2587	Jandaia do Sul	4112108
8811	2587	Janiopolis	4112207
8812	2587	Japira	4112306
8813	2587	Japura	4112405
8814	2587	Jardim Alegre	4112504
8815	2587	Jardim Olinda	4112603
8816	2587	Jataizinho	4112702
8817	2587	Jesuitas	4112751
8818	2587	Joaquim Tavora	4112801
8819	2587	Jundiai do Sul	4112900
8820	2587	Juranda	4112959
8821	2587	Jussara	4113007
8822	2587	Kalore	4113106
8823	2587	Lapa	4113205
8824	2587	Laranjal	4113254
8825	2587	Laranjeiras do Sul	4113304
8826	2587	Leopolis	4113403
8827	2587	Lidianopolis	4113429
8828	2587	Lindoeste	4113452
8829	2587	Loanda	4113502
8830	2587	Lobato	4113601
8831	2587	Londrina	4113700
8832	2587	Luiziana	4113734
8833	2587	Lunardelli	4113759
8834	2587	Lupionopolis	4113809
8835	2587	Mallet	4113908
8836	2587	Mambore	4114005
8837	2587	Mandaguacu	4114104
8838	2587	Mandaguari	4114203
8839	2587	Mandirituba	4114302
8840	2587	Manfrinopolis	4114351
8841	2587	Mangueirinha	4114401
8842	2587	Manoel Ribas	4114500
8843	2587	Marechal Candido Rondon	4114609
8844	2587	Maria Helena	4114708
8845	2587	Marialva	4114807
8846	2587	Marilandia do Sul	4114906
8847	2587	Marilena	4115002
8848	2587	Mariluz	4115101
8849	2587	Maringa	4115200
8850	2587	Mariopolis	4115309
8851	2587	Maripa	4115358
8852	2587	Marmeleiro	4115408
8853	2587	Marquinho	4115457
8854	2587	Marumbi	4115507
8855	2587	Matelandia	4115606
8856	2587	Matinhos	4115705
8857	2587	Mato Rico	4115739
8858	2587	Maua da Serra	4115754
8859	2587	Medianeira	4115804
8860	2587	Mercedes	4115853
8861	2587	Mirador	4115903
8862	2587	Miraselva	4116000
8863	2587	Missal	4116059
8864	2587	Moreira Sales	4116109
8865	2587	Morretes	4116208
8866	2587	Munhoz de Melo	4116307
8867	2587	Nossa Senhora das Gracas	4116406
8868	2587	Nova Alianca do Ivai	4116505
8869	2587	Nova America da Colina	4116604
8870	2587	Nova Aurora	4116703
8871	2587	Nova Cantu	4116802
8872	2587	Nova Esperanca	4116901
8873	2587	Nova Esperanca do Sudoeste	4116950
8874	2587	Nova Fatima	4117008
8875	2587	Nova Laranjeiras	4117057
8876	2587	Nova Londrina	4117107
8877	2587	Nova Olimpia	4117206
8878	2587	Nova Santa Barbara	4117214
8879	2587	Nova Santa Rosa	4117222
8880	2587	Nova Prata do Iguacu	4117255
8881	2587	Nova Tebas	4117271
8882	2587	Novo Itacolomi	4117297
8883	2587	Ortigueira	4117305
8884	2587	Ourizona	4117404
8885	2587	Ouro Verde do Oeste	4117453
8886	2587	Paicandu	4117503
8887	2587	Palmas	4117602
8888	2587	Palmeira	4117701
8889	2587	Palmital	4117800
8890	2587	Palotina	4117909
8891	2587	Paraiso do Norte	4118006
8892	2587	Paranacity	4118105
8893	2587	Paranagua	4118204
8894	2587	Paranapoema	4118303
8895	2587	Paranavai	4118402
8896	2587	Pato Bragado	4118451
8897	2587	Pato Branco	4118501
8898	2587	Paula Freitas	4118600
8899	2587	Paulo Frontin	4118709
8900	2587	Peabiru	4118808
8901	2587	Perobal	4118857
8902	2587	Perola	4118907
8903	2587	Perola d'Oeste	4119004
8904	2587	Pien	4119103
8905	2587	Pinhais	4119152
8906	2587	Pinhalao	4119202
8907	2587	Pinhal de Sao Bento	4119251
8908	2587	Pinhao	4119301
8909	2587	Pirai do Sul	4119400
8910	2587	Piraquara	4119509
8911	2587	Pitanga	4119608
8912	2587	Pitangueiras	4119657
8913	2587	Planaltina do Parana	4119707
8914	2587	Planalto	4119806
8915	2587	Ponta Grossa	4119905
8916	2587	Pontal do Parana	4119954
8917	2587	Porecatu	4120002
8918	2587	Porto Amazonas	4120101
8919	2587	Porto Barreiro	4120150
8920	2587	Porto Rico	4120200
8921	2587	Porto Vitoria	4120309
8922	2587	Prado Ferreira	4120333
8923	2587	Pranchita	4120358
8924	2587	Presidente Castelo Branco	4120408
8925	2587	Primeiro de Maio	4120507
8926	2587	Prudentopolis	4120606
8927	2587	Quarto Centenario	4120655
8928	2587	Quatigua	4120705
8929	2587	Quatro Barras	4120804
8930	2587	Quatro Pontes	4120853
8931	2587	Quedas do Iguacu	4120903
8932	2587	Querencia do Norte	4121000
8933	2587	Quinta do Sol	4121109
8934	2587	Quitandinha	4121208
8935	2587	Ramilandia	4121257
8936	2587	Rancho Alegre	4121307
8937	2587	Rancho Alegre D'Oeste	4121356
8938	2587	Realeza	4121406
8939	2587	Reboucas	4121505
8940	2587	Renascenca	4121604
8941	2587	Reserva	4121703
8942	2587	Reserva do Iguacu	4121752
8943	2587	Ribeirao Claro	4121802
8944	2587	Ribeirao do Pinhal	4121901
8945	2587	Rio Azul	4122008
8946	2587	Rio Bom	4122107
8947	2587	Rio Bonito do Iguacu	4122156
8948	2587	Rio Branco do Ivai	4122172
8949	2587	Rio Branco do Sul	4122206
8950	2587	Rio Negro	4122305
8951	2587	Rolandia	4122404
8952	2587	Roncador	4122503
8953	2587	Rondon	4122602
8954	2587	Rosario do Ivai	4122651
8955	2587	Sabaudia	4122701
8956	2587	Salgado Filho	4122800
8957	2587	Salto do Itarare	4122909
8958	2587	Salto do Lontra	4123006
8959	2587	Santa Amelia	4123105
8960	2587	Santa Cecilia do Pavao	4123204
8961	2587	Santa Cruz de Monte Castelo	4123303
8962	2587	Santa Fe	4123402
8963	2587	Santa Helena	4123501
8964	2587	Santa Ines	4123600
8965	2587	Santa Isabel do Ivai	4123709
8966	2587	Santa Izabel do Oeste	4123808
8967	2587	Santa Lucia	4123824
8968	2587	Santa Maria do Oeste	4123857
8969	2587	Santa Mariana	4123907
8970	2587	Santa Monica	4123956
8971	2587	Santana do Itarare	4124004
8972	2587	Santa Tereza do Oeste	4124020
8973	2587	Santa Terezinha de Itaipu	4124053
8974	2587	Santo Antonio da Platina	4124103
8975	2587	Santo Antonio do Caiua	4124202
8976	2587	Santo Antonio do Paraiso	4124301
8977	2587	Santo Antonio do Sudoeste	4124400
8978	2587	Santo Inacio	4124509
8979	2587	Sao Carlos do Ivai	4124608
8980	2587	Sao Jeronimo da Serra	4124707
8981	2587	Sao Joao	4124806
8982	2587	Sao Joao do Caiua	4124905
8983	2587	Sao Joao do Ivai	4125001
8984	2587	Sao Joao do Triunfo	4125100
8985	2587	Sao Jorge d'Oeste	4125209
8986	2587	Sao Jorge do Ivai	4125308
8987	2587	Sao Jorge do Patrocinio	4125357
8988	2587	Sao Jose da Boa Vista	4125407
8989	2587	Sao Jose das Palmeiras	4125456
8990	2587	Sao Jose dos Pinhais	4125506
8991	2587	Sao Manoel do Parana	4125555
8992	2587	Sao Mateus do Sul	4125605
8993	2587	Sao Miguel do Iguacu	4125704
8994	2587	Sao Pedro do Iguacu	4125753
8995	2587	Sao Pedro do Ivai	4125803
8996	2587	Sao Pedro do Parana	4125902
8997	2587	Sao Sebastiao da Amoreira	4126009
8998	2587	Sao Tome	4126108
8999	2587	Sapopema	4126207
9000	2587	Sarandi	4126256
9001	2587	Saudade do Iguacu	4126272
9002	2587	Senges	4126306
9003	2587	Serranopolis do Iguacu	4126355
9004	2587	Sertaneja	4126405
9005	2587	Sertanopolis	4126504
9006	2587	Siqueira Campos	4126603
9007	2587	Sulina	4126652
9008	2587	Tamarana	4126678
9009	2587	Tamboara	4126702
9010	2587	Tapejara	4126801
9011	2587	Tapira	4126900
9012	2587	Teixeira Soares	4127007
9013	2587	Telemaco Borba	4127106
9014	2587	Terra Boa	4127205
9015	2587	Terra Rica	4127304
9016	2587	Terra Roxa	4127403
9017	2587	Tibagi	4127502
9018	2587	Tijucas do Sul	4127601
9019	2587	Toledo	4127700
9020	2587	Tomazina	4127809
9021	2587	Tres Barras do Parana	4127858
9022	2587	Tunas do Parana	4127882
9023	2587	Tuneiras do Oeste	4127908
9024	2587	Tupassi	4127957
9025	2587	Turvo	4127965
9026	2587	Ubirata	4128005
9027	2587	Umuarama	4128104
9028	2587	Uniao da Vitoria	4128203
9029	2587	Uniflor	4128302
9030	2587	Urai	4128401
9031	2587	Wenceslau Braz	4128500
9032	2587	Ventania	4128534
9033	2587	Vera Cruz do Oeste	4128559
9034	2587	Vere	4128609
9035	2587	Vila Alta	4128625
9036	2587	Doutor Ulysses	4128633
9037	2587	Virmond	4128658
9038	2587	Vitorino	4128708
9039	2587	Xambre	4128807
9040	2595	Abdon Batista	4200051
9041	2595	Abelardo Luz	4200101
9042	2595	Agrolandia	4200200
9043	2595	Agronomica	4200309
9044	2595	Agua Doce	4200408
9045	2595	Aguas de Chapeco	4200507
9046	2595	Aguas Frias	4200556
9047	2595	Aguas Mornas	4200606
9048	2595	Alfredo Wagner	4200705
9049	2595	Alto Bela Vista	4200754
9050	2595	Anchieta	4200804
9051	2595	Angelina	4200903
9052	2595	Anita Garibaldi	4201000
9053	2595	Anitapolis	4201109
9054	2595	Antonio Carlos	4201208
9055	2595	Apiuna	4201257
9056	2595	Arabuta	4201273
9057	2595	Araquari	4201307
9058	2595	Ararangua	4201406
9059	2595	Armazem	4201505
9060	2595	Arroio Trinta	4201604
9061	2595	Arvoredo	4201653
9062	2595	Ascurra	4201703
9063	2595	Atalanta	4201802
9064	2595	Aurora	4201901
9065	2595	Balneario Arroio do Silva	4201950
9066	2595	Balneario Camboriu	4202008
9067	2595	Balneario Barra do Sul	4202057
9068	2595	Balneario Gaivota	4202073
9069	2595	Bandeirante	4202081
9070	2595	Barra Bonita	4202099
9071	2595	Barra Velha	4202107
9072	2595	Bela Vista do Toldo	4202131
9073	2595	Belmonte	4202156
9074	2595	Benedito Novo	4202206
9075	2595	Biguacu	4202305
9076	2595	Blumenau	4202404
9077	2595	Bocaina do Sul	4202438
9078	2595	Bombinhas	4202453
9079	2595	Bom Jardim da Serra	4202503
9080	2595	Bom Jesus	4202537
9081	2595	Bom Jesus do Oeste	4202578
9082	2595	Bom Retiro	4202602
9083	2595	Botuvera	4202701
9084	2595	Braco do Norte	4202800
9085	2595	Braco do Trombudo	4202859
9086	2595	Brunopolis	4202875
9087	2595	Brusque	4202909
9088	2595	Cacador	4203006
9089	2595	Caibi	4203105
9090	2595	Calmon	4203154
9091	2595	Camboriu	4203204
9092	2595	Capao Alto	4203253
9093	2595	Campo Alegre	4203303
9094	2595	Campo Belo do Sul	4203402
9095	2595	Campo Ere	4203501
9096	2595	Campos Novos	4203600
9097	2595	Canelinha	4203709
9098	2595	Canoinhas	4203808
9099	2595	Capinzal	4203907
9100	2595	Capivari de Baixo	4203956
9101	2595	Catanduvas	4204004
9102	2595	Caxambu do Sul	4204103
9103	2595	Celso Ramos	4204152
9104	2595	Cerro Negro	4204178
9105	2595	Chapadao do Lageado	4204194
9106	2595	Chapeco	4204202
9107	2595	Cocal do Sul	4204251
9108	2595	Concordia	4204301
9109	2595	Cordilheira Alta	4204350
9110	2595	Coronel Freitas	4204400
9111	2595	Coronel Martins	4204459
9112	2595	Corupa	4204509
9113	2595	Correia Pinto	4204558
9114	2595	Criciuma	4204608
9115	2595	Cunha Pora	4204707
9116	2595	Cunhatai	4204756
9117	2595	Curitibanos	4204806
9118	2595	Descanso	4204905
9119	2595	Dionisio Cerqueira	4205001
9120	2595	Dona Emma	4205100
9121	2595	Doutor Pedrinho	4205159
9122	2595	Entre Rios	4205175
9123	2595	Ermo	4205191
9124	2595	Erval Velho	4205209
9125	2595	Faxinal dos Guedes	4205308
9126	2595	Flor do Sertao	4205357
9127	2595	Florianopolis	4205407
9128	2595	Formosa do Sul	4205431
9129	2595	Forquilhinha	4205456
9130	2595	Fraiburgo	4205506
9131	2595	Frei Rogerio	4205555
9132	2595	Galvao	4205605
9133	2595	Garopaba	4205704
9134	2595	Garuva	4205803
9135	2595	Gaspar	4205902
9136	2595	Governador Celso Ramos	4206009
9137	2595	Grao Para	4206108
9138	2595	Gravatal	4206207
9139	2595	Guabiruba	4206306
9140	2595	Guaraciaba	4206405
9141	2595	Guaramirim	4206504
9142	2595	Guaruja do Sul	4206603
9143	2595	Guatambu	4206652
9144	2595	Herval d'Oeste	4206702
9145	2595	Ibiam	4206751
9146	2595	Ibicare	4206801
9147	2595	Ibirama	4206900
9148	2595	Icara	4207007
9149	2595	Ilhota	4207106
9150	2595	Imarui	4207205
9151	2595	Imbituba	4207304
9152	2595	Imbuia	4207403
9153	2595	Indaial	4207502
9154	2595	Iomere	4207577
9155	2595	Ipira	4207601
9156	2595	Ipora do Oeste	4207650
9157	2595	Ipuacu	4207684
9158	2595	Ipumirim	4207700
9159	2595	Iraceminha	4207759
9160	2595	Irani	4207809
9161	2595	Irati	4207858
9162	2595	Irineopolis	4207908
9163	2595	Ita	4208005
9164	2595	Itaiopolis	4208104
9165	2595	Itajai	4208203
9166	2595	Itapema	4208302
9167	2595	Itapiranga	4208401
9168	2595	Itapoa	4208450
9169	2595	Ituporanga	4208500
9170	2595	Jabora	4208609
9171	2595	Jacinto Machado	4208708
9172	2595	Jaguaruna	4208807
9173	2595	Jaragua do Sul	4208906
9174	2595	Jardinopolis	4208955
9175	2595	Joacaba	4209003
9176	2595	Joinville	4209102
9177	2595	Jose Boiteux	4209151
9178	2595	Jupia	4209177
9179	2595	Lacerdopolis	4209201
9180	2595	Lages	4209300
9181	2595	Laguna	4209409
9182	2595	Lajeado Grande	4209458
9183	2595	Laurentino	4209508
9184	2595	Lauro Muller	4209607
9185	2595	Lebon Regis	4209706
9186	2595	Leoberto Leal	4209805
9187	2595	Lindoia do Sul	4209854
9188	2595	Lontras	4209904
9189	2595	Luiz Alves	4210001
9190	2595	Luzerna	4210035
9191	2595	Macieira	4210050
9192	2595	Mafra	4210100
9193	2595	Major Gercino	4210209
9194	2595	Major Vieira	4210308
9195	2595	Maracaja	4210407
9196	2595	Maravilha	4210506
9197	2595	Marema	4210555
9198	2595	Massaranduba	4210605
9199	2595	Matos Costa	4210704
9200	2595	Meleiro	4210803
9201	2595	Mirim Doce	4210852
9202	2595	Modelo	4210902
9203	2595	Mondai	4211009
9204	2595	Monte Carlo	4211058
9205	2595	Monte Castelo	4211108
9206	2595	Morro da Fumaca	4211207
9207	2595	Morro Grande	4211256
9208	2595	Navegantes	4211306
9209	2595	Nova Erechim	4211405
9210	2595	Nova Itaberaba	4211454
9211	2595	Nova Trento	4211504
9212	2595	Nova Veneza	4211603
9213	2595	Novo Horizonte	4211652
9214	2595	Orleans	4211702
9215	2595	Otacilio Costa	4211751
9216	2595	Ouro	4211801
9217	2595	Ouro Verde	4211850
9218	2595	Paial	4211876
9219	2595	Painel	4211892
9220	2595	Palhoca	4211900
9221	2595	Palma Sola	4212007
9222	2595	Palmeira	4212056
9223	2595	Palmitos	4212106
9224	2595	Papanduva	4212205
9225	2595	Paraiso	4212239
9226	2595	Passo de Torres	4212254
9227	2595	Passos Maia	4212270
9228	2595	Paulo Lopes	4212304
9229	2595	Pedras Grandes	4212403
9230	2595	Penha	4212502
9231	2595	Peritiba	4212601
9232	2595	Petrolandia	4212700
9233	2595	Picarras	4212809
9234	2595	Pinhalzinho	4212908
9235	2595	Pinheiro Preto	4213005
9236	2595	Piratuba	4213104
9237	2595	Planalto Alegre	4213153
9238	2595	Pomerode	4213203
9239	2595	Ponte Alta	4213302
9240	2595	Ponte Alta do Norte	4213351
9241	2595	Ponte Serrada	4213401
9242	2595	Porto Belo	4213500
9243	2595	Porto Uniao	4213609
9244	2595	Pouso Redondo	4213708
9245	2595	Praia Grande	4213807
9246	2595	Presidente Castelo Branco	4213906
9247	2595	Presidente Getulio	4214003
9248	2595	Presidente Nereu	4214102
9249	2595	Princesa	4214151
9250	2595	Quilombo	4214201
9251	2595	Rancho Queimado	4214300
9252	2595	Rio das Antas	4214409
9253	2595	Rio do Campo	4214508
9254	2595	Rio do Oeste	4214607
9255	2595	Rio dos Cedros	4214706
9256	2595	Rio do Sul	4214805
9257	2595	Rio Fortuna	4214904
9258	2595	Rio Negrinho	4215000
9259	2595	Rio Rufino	4215059
9260	2595	Riqueza	4215075
9261	2595	Rodeio	4215109
9262	2595	Romelandia	4215208
9263	2595	Salete	4215307
9264	2595	Saltinho	4215356
9265	2595	Salto Veloso	4215406
9266	2595	Sangao	4215455
9267	2595	Santa Cecilia	4215505
9268	2595	Santa Helena	4215554
9269	2595	Santa Rosa de Lima	4215604
9270	2595	Santa Rosa do Sul	4215653
9271	2595	Santa Terezinha	4215679
9272	2595	Santa Terezinha do Progresso	4215687
9273	2595	Santiago do Sul	4215695
9274	2595	Santo Amaro da Imperatriz	4215703
9275	2595	Sao Bernardino	4215752
9276	2595	Sao Bento do Sul	4215802
9277	2595	Sao Bonifacio	4215901
9278	2595	Sao Carlos	4216008
9279	2595	Sao Cristovao do Sul	4216057
9280	2595	Sao Domingos	4216107
9281	2595	Sao Francisco do Sul	4216206
9282	2595	Sao Joao do Oeste	4216255
9283	2595	Sao Joao Batista	4216305
9284	2595	Sao Joao do Itaperiu	4216354
9285	2595	Sao Joao do Sul	4216404
9286	2595	Sao Joaquim	4216503
9287	2595	Sao Jose	4216602
9288	2595	Sao Jose do Cedro	4216701
9289	2595	Sao Jose do Cerrito	4216800
9290	2595	Sao Lourenco do Oeste	4216909
9291	2595	Sao Ludgero	4217006
9292	2595	Sao Martinho	4217105
9293	2595	Sao Miguel da Boa Vista	4217154
9294	2595	Sao Miguel D'Oeste	4217204
9295	2595	Sao Pedro de Alcantara	4217253
9296	2595	Saudades	4217303
9297	2595	Schroeder	4217402
9298	2595	Seara	4217501
9299	2595	Serra Alta	4217550
9300	2595	Sideropolis	4217600
9301	2595	Sombrio	4217709
9302	2595	Sul Brasil	4217758
9303	2595	Taio	4217808
9304	2595	Tangara	4217907
9305	2595	Tigrinhos	4217956
9306	2595	Tijucas	4218004
9307	2595	Timbe do Sul	4218103
9308	2595	Timbo	4218202
9309	2595	Timbo Grande	4218251
9310	2595	Tres Barras	4218301
9311	2595	Treviso	4218350
9312	2595	Treze de Maio	4218400
9313	2595	Treze Tilias	4218509
9314	2595	Trombudo Central	4218608
9315	2595	Tubarao	4218707
9316	2595	Tunapolis	4218756
9317	2595	Turvo	4218806
9318	2595	Uniao do Oeste	4218855
9319	2595	Urubici	4218905
9320	2595	Urupema	4218954
9321	2595	Urussanga	4219002
9322	2595	Vargeao	4219101
9323	2595	Vargem	4219150
9324	2595	Vargem Bonita	4219176
9325	2595	Vidal Ramos	4219200
9326	2595	Videira	4219309
9327	2595	Vitor Meireles	4219358
9328	2595	Witmarsum	4219408
9329	2595	Xanxere	4219507
9330	2595	Xavantina	4219606
9331	2595	Xaxim	4219705
9332	2595	Zortea	4219853
9333	2592	ACEGUA	4300034
9334	2592	Agua Santa	4300059
9335	2592	Agudo	4300109
9336	2592	Ajuricaba	4300208
9337	2592	Alecrim	4300307
9338	2592	Alegrete	4300406
9339	2592	Alegria	4300455
9340	2592	ALMIRANTE TAMANDARE DO SUL	4300471
9341	2592	Alpestre	4300505
9342	2592	Alto Alegre	4300554
9343	2592	Alto Feliz	4300570
9344	2592	Alvorada	4300604
9345	2592	Amaral Ferrador	4300638
9346	2592	Ametista do Sul	4300646
9347	2592	Andre da Rocha	4300661
9348	2592	Anta Gorda	4300703
9349	2592	Antonio Prado	4300802
9350	2592	Arambare	4300851
9351	2592	Ararica	4300877
9352	2592	Aratiba	4300901
9353	2592	Arroio do Meio	4301008
9354	2592	Arroio do Sal	4301057
9355	2592	ARROIO DO PADRE	4301073
9356	2592	Arroio dos Ratos	4301107
9357	2592	Arroio do Tigre	4301206
9358	2592	Arroio Grande	4301305
9359	2592	Arvorezinha	4301404
9360	2592	Augusto Pestana	4301503
9361	2592	Aurea	4301552
9362	2592	Bage	4301602
9363	2592	Balneario Pinhal	4301636
9364	2592	Barao	4301651
9365	2592	Barao de Cotegipe	4301701
9366	2592	Barao do Triunfo	4301750
9367	2592	Barracao	4301800
9368	2592	Barra do Guarita	4301859
9369	2592	Barra do Quarai	4301875
9370	2592	Barra do Ribeiro	4301909
9371	2592	Barra do Rio Azul	4301925
9372	2592	Barra Funda	4301958
9373	2592	Barros Cassal	4302006
9374	2592	Benjamin Constant do Sul	4302055
9375	2592	Bento Goncalves	4302105
9376	2592	Boa Vista das Missoes	4302154
9377	2592	Boa Vista do Burica	4302204
9378	2592	BOA VISTA DO CADEADO	4302220
9379	2592	BOA VISTA DO INCRA	4302238
9380	2592	Boa Vista do Sul	4302253
9381	2592	Bom Jesus	4302303
9382	2592	Bom Principio	4302352
9383	2592	Bom Progresso	4302378
9384	2592	Bom Retiro do Sul	4302402
9385	2592	Boqueirao do Leao	4302451
9386	2592	Bossoroca	4302501
9387	2592	BOZANO	4302584
9388	2592	Braga	4302600
9389	2592	Brochier	4302659
9390	2592	Butia	4302709
9391	2592	Cacapava do Sul	4302808
9392	2592	Cacequi	4302907
9393	2592	Cachoeira do Sul	4303004
9394	2592	Cachoeirinha	4303103
9395	2592	Cacique Doble	4303202
9396	2592	Caibate	4303301
9397	2592	Caicara	4303400
9398	2592	Camaqua	4303509
9399	2592	Camargo	4303558
9400	2592	Cambara do Sul	4303608
9401	2592	Campestre da Serra	4303673
9402	2592	Campina das Missoes	4303707
9403	2592	Campinas do Sul	4303806
9404	2592	Campo Bom	4303905
9405	2592	Campo Novo	4304002
9406	2592	Campos Borges	4304101
9407	2592	Candelaria	4304200
9408	2592	Candido Godoi	4304309
9409	2592	Candiota	4304358
9410	2592	Canela	4304408
9411	2592	Cangucu	4304507
9412	2592	Canoas	4304606
9413	2592	CANUDOS DO VALE	4304614
9414	2592	CAPAO BONITO DO SUL	4304622
9415	2592	Capao da Canoa	4304630
9416	2592	CAPAO DO CIPO	4304655
9417	2592	Capao do Leao	4304663
9418	2592	Capivari do Sul	4304671
9419	2592	Capela de Santana	4304689
9420	2592	Capitao	4304697
9421	2592	Carazinho	4304705
9422	2592	Caraa	4304713
9423	2592	Carlos Barbosa	4304804
9424	2592	Carlos Gomes	4304853
9425	2592	Casca	4304903
9426	2592	Caseiros	4304952
9427	2592	Catuipe	4305009
9428	2592	Caxias do Sul	4305108
9429	2592	Centenario	4305116
9430	2592	Cerrito	4305124
9431	2592	Cerro Branco	4305132
9432	2592	Cerro Grande	4305157
9433	2592	Cerro Grande do Sul	4305173
9434	2592	Cerro Largo	4305207
9435	2592	Chapada	4305306
9436	2592	Charqueadas	4305355
9437	2592	Charrua	4305371
9438	2592	Chiapeta	4305405
9439	2592	Chui	4305439
9440	2592	Chuvisca	4305447
9441	2592	Cidreira	4305454
9442	2592	Ciriaco	4305504
9443	2592	Colinas	4305587
9444	2592	Colorado	4305603
9445	2592	Condor	4305702
9446	2592	Constantina	4305801
9447	2592	COQUEIRO BAIXO	4305835
9448	2592	Coqueiros do Sul	4305850
9449	2592	Coronel Barros	4305871
9450	2592	Coronel Bicaco	4305900
9451	2592	CORONEL PILAR	4305934
9452	2592	Cotipora	4305959
9453	2592	Coxilha	4305975
9454	2592	Crissiumal	4306007
9455	2592	Cristal	4306056
9456	2592	Cristal do Sul	4306072
9457	2592	Cruz Alta	4306106
9458	2592	CRUZALTENSE	4306130
9459	2592	Cruzeiro do Sul	4306205
9460	2592	David Canabarro	4306304
9461	2592	Derrubadas	4306320
9462	2592	Dezesseis de Novembro	4306353
9463	2592	Dilermando de Aguiar	4306379
9464	2592	Dois Irmaos	4306403
9465	2592	Dois Irmaos das Missoes	4306429
9466	2592	Dois Lajeados	4306452
9467	2592	Dom Feliciano	4306502
9468	2592	Dom Pedro de Alcantara	4306551
9469	2592	Dom Pedrito	4306601
9470	2592	Dona Francisca	4306700
9471	2592	Doutor Mauricio Cardoso	4306734
9472	2592	Doutor Ricardo	4306759
9473	2592	Eldorado do Sul	4306767
9474	2592	Encantado	4306809
9475	2592	Encruzilhada do Sul	4306908
9476	2592	Engenho Velho	4306924
9477	2592	Entre-Ijuis	4306932
9478	2592	Entre Rios do Sul	4306957
9479	2592	Erebango	4306973
9480	2592	Erechim	4307005
9481	2592	Ernestina	4307054
9482	2592	Herval	4307104
9483	2592	Erval Grande	4307203
9484	2592	Erval Seco	4307302
9485	2592	Esmeralda	4307401
9486	2592	Esperanca do Sul	4307450
9487	2592	Espumoso	4307500
9488	2592	Estacao	4307559
9489	2592	Estancia Velha	4307609
9490	2592	Esteio	4307708
9491	2592	Estrela	4307807
9492	2592	Estrela Velha	4307815
9493	2592	Eugenio de Castro	4307831
9494	2592	Fagundes Varela	4307864
9495	2592	Farroupilha	4307906
9496	2592	Faxinal do Soturno	4308003
9497	2592	Faxinalzinho	4308052
9498	2592	Fazenda Vilanova	4308078
9499	2592	Feliz	4308102
9500	2592	Flores da Cunha	4308201
9501	2592	Floriano Peixoto	4308250
9502	2592	Fontoura Xavier	4308300
9503	2592	Formigueiro	4308409
9504	2592	FORQUETINHA	4308433
9505	2592	Fortaleza dos Valos	4308458
9506	2592	Frederico Westphalen	4308508
9507	2592	Garibaldi	4308607
9508	2592	Garruchos	4308656
9509	2592	Gaurama	4308706
9510	2592	General Camara	4308805
9511	2592	Gentil	4308854
9512	2592	Getulio Vargas	4308904
9513	2592	Girua	4309001
9514	2592	Glorinha	4309050
9515	2592	Gramado	4309100
9516	2592	Gramado dos Loureiros	4309126
9517	2592	Gramado Xavier	4309159
9518	2592	Gravatai	4309209
9519	2592	Guabiju	4309258
9520	2592	Guaiba	4309308
9521	2592	Guapore	4309407
9522	2592	Guarani das Missoes	4309506
9523	2592	Harmonia	4309555
9524	2592	Herveiras	4309571
9525	2592	Horizontina	4309605
9526	2592	Hulha Negra	4309654
9527	2592	Humaita	4309704
9528	2592	Ibarama	4309753
9529	2592	Ibiaca	4309803
9530	2592	Ibiraiaras	4309902
9531	2592	Ibirapuita	4309951
9532	2592	Ibiruba	4310009
9533	2592	Igrejinha	4310108
9534	2592	Ijui	4310207
9535	2592	Ilopolis	4310306
9536	2592	Imbe	4310330
9537	2592	Imigrante	4310363
9538	2592	Independencia	4310405
9539	2592	Inhacora	4310413
9540	2592	Ipe	4310439
9541	2592	Ipiranga do Sul	4310462
9542	2592	Irai	4310504
9543	2592	Itaara	4310538
9544	2592	Itacurubi	4310553
9545	2592	Itapuca	4310579
9546	2592	Itaqui	4310603
9547	2592	ITATI	4310652
9548	2592	Itatiba do Sul	4310702
9549	2592	Ivora	4310751
9550	2592	Ivoti	4310801
9551	2592	Jaboticaba	4310850
9552	2592	JACUIZINHO	4310876
9553	2592	Jacutinga	4310900
9554	2592	Jaguarao	4311007
9555	2592	Jaguari	4311106
9556	2592	Jaquirana	4311122
9557	2592	Jari	4311130
9558	2592	Joia	4311155
9559	2592	Julio de Castilhos	4311205
9560	2592	LAGOA BONITA DO SUL	4311239
9561	2592	Lagoao	4311254
9562	2592	Lagoa dos Tres Cantos	4311270
9563	2592	Lagoa Vermelha	4311304
9564	2592	Lajeado	4311403
9565	2592	Lajeado do Bugre	4311429
9566	2592	Lavras do Sul	4311502
9567	2592	Liberato Salzano	4311601
9568	2592	Lindolfo Collor	4311627
9569	2592	Linha Nova	4311643
9570	2592	Machadinho	4311700
9571	2592	Macambara	4311718
9572	2592	Mampituba	4311734
9573	2592	Manoel Viana	4311759
9574	2592	Maquine	4311775
9575	2592	Marata	4311791
9576	2592	Marau	4311809
9577	2592	Marcelino Ramos	4311908
9578	2592	Mariana Pimentel	4311981
9579	2592	Mariano Moro	4312005
9580	2592	Marques de Souza	4312054
9581	2592	Mata	4312104
9582	2592	Mato Castelhano	4312138
9583	2592	Mato Leitao	4312153
9584	2592	MATO QUEIMADO	4312179
9585	2592	Maximiliano de Almeida	4312203
9586	2592	Minas do Leao	4312252
9587	2592	Miraguai	4312302
9588	2592	Montauri	4312351
9589	2592	Monte Alegre dos Campos	4312377
9590	2592	Monte Belo do Sul	4312385
9591	2592	Montenegro	4312401
9592	2592	Mormaco	4312427
9593	2592	Morrinhos do Sul	4312443
9594	2592	Morro Redondo	4312450
9595	2592	Morro Reuter	4312476
9596	2592	Mostardas	4312500
9597	2592	Mucum	4312609
9598	2592	Muitos Capoes	4312617
9599	2592	Muliterno	4312625
9600	2592	Nao-Me-Toque	4312658
9601	2592	Nicolau Vergueiro	4312674
9602	2592	Nonoai	4312708
9603	2592	Nova Alvorada	4312757
9604	2592	Nova Araca	4312807
9605	2592	Nova Bassano	4312906
9606	2592	Nova Boa Vista	4312955
9607	2592	Nova Brescia	4313003
9608	2592	Nova Candelaria	4313011
9609	2592	Nova Esperanca do Sul	4313037
9610	2592	Nova Hartz	4313060
9611	2592	Nova Padua	4313086
9612	2592	Nova Palma	4313102
9613	2592	Nova Petropolis	4313201
9614	2592	Nova Prata	4313300
9615	2592	Nova Ramada	4313334
9616	2592	Nova Roma do Sul	4313359
9617	2592	Nova Santa Rita	4313375
9618	2592	Novo Cabrais	4313391
9619	2592	Novo Hamburgo	4313409
9620	2592	Novo Machado	4313425
9621	2592	Novo Tiradentes	4313441
9622	2592	NOVO XINGU	4313466
9623	2592	Novo Barreiro	4313490
9624	2592	Osorio	4313508
9625	2592	Paim Filho	4313607
9626	2592	Palmares do Sul	4313656
9627	2592	Palmeira das Missoes	4313706
9628	2592	Palmitinho	4313805
9629	2592	Panambi	4313904
9630	2592	Pantano Grande	4313953
9631	2592	Parai	4314001
9632	2592	Paraiso do Sul	4314027
9633	2592	Pareci Novo	4314035
9634	2592	Parobe	4314050
9635	2592	Passa Sete	4314068
9636	2592	Passo do Sobrado	4314076
9637	2592	Passo Fundo	4314100
9638	2592	PAULO BENTO	4314134
9639	2592	Paverama	4314159
9640	2592	PEDRAS ALTAS	4314175
9641	2592	Pedro Osorio	4314209
9642	2592	Pejucara	4314308
9643	2592	Pelotas	4314407
9644	2592	Picada Cafe	4314423
9645	2592	Pinhal	4314456
9646	2592	PINHAL DA SERRA	4314464
9647	2592	Pinhal Grande	4314472
9648	2592	Pinheirinho do Vale	4314498
9649	2592	Pinheiro Machado	4314506
9650	2592	Pirapo	4314555
9651	2592	Piratini	4314605
9652	2592	Planalto	4314704
9653	2592	Poco das Antas	4314753
9654	2592	Pontao	4314779
9655	2592	Ponte Preta	4314787
9656	2592	Portao	4314803
9657	2592	Porto Alegre	4314902
9658	2592	Porto Lucena	4315008
9659	2592	Porto Maua	4315057
9660	2592	Porto Vera Cruz	4315073
9661	2592	Porto Xavier	4315107
9662	2592	Pouso Novo	4315131
9663	2592	Presidente Lucena	4315149
9664	2592	Progresso	4315156
9665	2592	Protasio Alves	4315172
9666	2592	Putinga	4315206
9667	2592	Quarai	4315305
9668	2592	QUATRO IRMAOS	4315313
9669	2592	Quevedos	4315321
9670	2592	Quinze de Novembro	4315354
9671	2592	Redentora	4315404
9672	2592	Relvado	4315453
9673	2592	Restinga Seca	4315503
9674	2592	Rio dos Indios	4315552
9675	2592	Rio Grande	4315602
9676	2592	Rio Pardo	4315701
9677	2592	Riozinho	4315750
9678	2592	Roca Sales	4315800
9679	2592	Rodeio Bonito	4315909
9680	2592	ROLADOR	4315958
9681	2592	Rolante	4316006
9682	2592	Ronda Alta	4316105
9683	2592	Rondinha	4316204
9684	2592	Roque Gonzales	4316303
9685	2592	Rosario do Sul	4316402
9686	2592	Sagrada Familia	4316428
9687	2592	Saldanha Marinho	4316436
9688	2592	Salto do Jacui	4316451
9689	2592	Salvador das Missoes	4316477
9690	2592	Salvador do Sul	4316501
9691	2592	Sananduva	4316600
9692	2592	Santa Barbara do Sul	4316709
9693	2592	SANTA CECILIA DO SUL	4316733
9694	2592	Santa Clara do Sul	4316758
9695	2592	Santa Cruz do Sul	4316808
9696	2592	Santa Maria	4316907
9697	2592	Santa Maria do Herval	4316956
9698	2592	SANTA MARGARIDA DO SUL	4316972
9699	2592	Santana da Boa Vista	4317004
9700	2592	Santana do Livramento	4317103
9701	2592	Santa Rosa	4317202
9702	2592	Santa Tereza	4317251
9703	2592	Santa Vitoria do Palmar	4317301
9704	2592	Santiago	4317400
9705	2592	Santo Angelo	4317509
9706	2592	Santo Antonio do Palma	4317558
9707	2592	Santo Antonio da Patrulha	4317608
9708	2592	Santo Antonio das Missoes	4317707
9709	2592	Santo Antonio do Planalto	4317756
9710	2592	Santo Augusto	4317806
9711	2592	Santo Cristo	4317905
9712	2592	Santo Expedito do Sul	4317954
9713	2592	Sao Borja	4318002
9714	2592	Sao Domingos do Sul	4318051
9715	2592	Sao Francisco de Assis	4318101
9716	2592	Sao Francisco de Paula	4318200
9717	2592	Sao Gabriel	4318309
9718	2592	Sao Jeronimo	4318408
9719	2592	Sao Joao da Urtiga	4318424
9720	2592	Sao Joao do Polesine	4318432
9721	2592	Sao Jorge	4318440
9722	2592	Sao Jose das Missoes	4318457
9723	2592	Sao Jose do Herval	4318465
9724	2592	Sao Jose do Hortencio	4318481
9725	2592	Sao Jose do Inhacora	4318499
9726	2592	Sao Jose do Norte	4318507
9727	2592	Sao Jose do Ouro	4318606
9728	2592	SAO JOSE DO SUL	4318614
9729	2592	Sao Jose dos Ausentes	4318622
9730	2592	Sao Leopoldo	4318705
9731	2592	Sao Lourenco do Sul	4318804
9732	2592	Sao Luiz Gonzaga	4318903
9733	2592	Sao Marcos	4319000
9734	2592	Sao Martinho	4319109
9735	2592	Sao Martinho da Serra	4319125
9736	2592	Sao Miguel das Missoes	4319158
9737	2592	Sao Nicolau	4319208
9738	2592	Sao Paulo das Missoes	4319307
9739	2592	Sao Pedro da Serra	4319356
9740	2592	SAO PEDRO DAS MISSOES	4319364
9741	2592	Sao Pedro do Butia	4319372
9742	2592	Sao Pedro do Sul	4319406
9743	2592	Sao Sebastiao do Cai	4319505
9744	2592	Sao Sepe	4319604
9745	2592	Sao Valentim	4319703
9746	2592	Sao Valentim do Sul	4319711
9747	2592	Sao Valerio do Sul	4319737
9748	2592	Sao Vendelino	4319752
9749	2592	Sao Vicente do Sul	4319802
9750	2592	Sapiranga	4319901
9751	2592	Sapucaia do Sul	4320008
9752	2592	Sarandi	4320107
9753	2592	Seberi	4320206
9754	2592	Sede Nova	4320230
9755	2592	Segredo	4320263
9756	2592	Selbach	4320305
9757	2592	Senador Salgado Filho	4320321
9758	2592	Sentinela do Sul	4320354
9759	2592	Serafina Correa	4320404
9760	2592	Serio	4320453
9761	2592	Sertao	4320503
9762	2592	Sertao Santana	4320552
9763	2592	Sete de Setembro	4320578
9764	2592	Severiano de Almeida	4320602
9765	2592	Silveira Martins	4320651
9766	2592	Sinimbu	4320677
9767	2592	Sobradinho	4320701
9768	2592	Soledade	4320800
9769	2592	Tabai	4320859
9770	2592	Tapejara	4320909
9771	2592	Tapera	4321006
9772	2592	Tapes	4321105
9773	2592	Taquara	4321204
9774	2592	Taquari	4321303
9775	2592	Taquarucu do Sul	4321329
9776	2592	Tavares	4321352
9777	2592	Tenente Portela	4321402
9778	2592	Terra de Areia	4321436
9779	2592	Teutonia	4321451
9780	2592	TIO HUGO	4321469
9781	2592	Tiradentes do Sul	4321477
9782	2592	Toropi	4321493
9783	2592	Torres	4321501
9784	2592	Tramandai	4321600
9785	2592	Travesseiro	4321626
9786	2592	Tres Arroios	4321634
9787	2592	Tres Cachoeiras	4321667
9788	2592	Tres Coroas	4321709
9789	2592	Tres de Maio	4321808
9790	2592	Tres Forquilhas	4321832
9791	2592	Tres Palmeiras	4321857
9792	2592	Tres Passos	4321907
9793	2592	Trindade do Sul	4321956
9794	2592	Triunfo	4322004
9795	2592	Tucunduva	4322103
9796	2592	Tunas	4322152
9797	2592	Tupanci do Sul	4322186
9798	2592	Tupancireta	4322202
9799	2592	Tupandi	4322251
9800	2592	Tuparendi	4322301
9801	2592	Turucu	4322327
9802	2592	Ubiretama	4322343
9803	2592	Uniao da Serra	4322350
9804	2592	Unistalda	4322376
9805	2592	Uruguaiana	4322400
9806	2592	Vacaria	4322509
9807	2592	Vale Verde	4322525
9808	2592	Vale do Sol	4322533
9809	2592	Vale Real	4322541
9810	2592	Vanini	4322558
9811	2592	Venancio Aires	4322608
9812	2592	Vera Cruz	4322707
9813	2592	Veranopolis	4322806
9814	2592	Vespasiano Correa	4322855
9815	2592	Viadutos	4322905
9816	2592	Viamao	4323002
9817	2592	Vicente Dutra	4323101
9818	2592	Victor Graeff	4323200
9819	2592	Vila Flores	4323309
9820	2592	Vila Langaro	4323358
9821	2592	Vila Maria	4323408
9822	2592	Vila Nova do Sul	4323457
9823	2592	Vista Alegre	4323507
9824	2592	Vista Alegre do Prata	4323606
9825	2592	Vista Gaucha	4323705
9826	2592	Vitoria das Missoes	4323754
9827	2592	WESTFALIA	4323770
9828	2592	Xangri-la	4323804
9829	2583	Agua Clara	5000203
9830	2583	Alcinopolis	5000252
9831	2583	Amambai	5000609
9832	2583	Anastacio	5000708
9833	2583	Anaurilandia	5000807
9834	2583	Angelica	5000856
9835	2583	Antonio Joao	5000906
9836	2583	Aparecida do Taboado	5001003
9837	2583	Aquidauana	5001102
9838	2583	Aral Moreira	5001243
9839	2583	Bandeirantes	5001508
9840	2583	Bataguassu	5001904
9841	2583	Bataipora	5002001
9842	2583	Bela Vista	5002100
9843	2583	Bodoquena	5002159
9844	2583	Bonito	5002209
9845	2583	Brasilandia	5002308
9846	2583	Caarapo	5002407
9847	2583	Camapua	5002605
9848	2583	Campo Grande	5002704
9849	2583	Caracol	5002803
9850	2583	Cassilandia	5002902
9851	2583	Chapadao do Sul	5002951
9852	2583	Corguinho	5003108
9853	2583	Coronel Sapucaia	5003157
9854	2583	Corumba	5003207
9855	2583	Costa Rica	5003256
9856	2583	Coxim	5003306
9857	2583	Deodapolis	5003454
9858	2583	Dois Irmaos do Buriti	5003488
9859	2583	Douradina	5003504
9860	2583	Dourados	5003702
9861	2583	Eldorado	5003751
9862	2583	Fatima do Sul	5003801
9863	2583	FIGUEIRAO	5003900
9864	2583	Gloria de Dourados	5004007
9865	2583	Guia Lopes da Laguna	5004106
9866	2583	Iguatemi	5004304
9867	2583	Inocencia	5004403
9868	2583	Itapora	5004502
9869	2583	Itaquirai	5004601
9870	2583	Ivinhema	5004700
9871	2583	Japora	5004809
9872	2583	Jaraguari	5004908
9873	2583	Jardim	5005004
9874	2583	Jatei	5005103
9875	2583	Juti	5005152
9876	2583	Ladario	5005202
9877	2583	Laguna Carapa	5005251
9878	2583	Maracaju	5005400
9879	2583	Miranda	5005608
9880	2583	Mundo Novo	5005681
9881	2583	Navirai	5005707
9882	2583	Nioaque	5005806
9883	2583	Nova Alvorada do Sul	5006002
9884	2583	Nova Andradina	5006200
9885	2583	Novo Horizonte do Sul	5006259
9886	2583	Paranaiba	5006309
9887	2583	Paranhos	5006358
9888	2583	Pedro Gomes	5006408
9889	2583	Ponta Pora	5006606
9890	2583	Porto Murtinho	5006903
9891	2583	Ribas do Rio Pardo	5007109
9892	2583	Rio Brilhante	5007208
9893	2583	Rio Negro	5007307
9894	2583	Rio Verde de Mato Grosso	5007406
9895	2583	Rochedo	5007505
9896	2583	Santa Rita do Pardo	5007554
9897	2583	Sao Gabriel do Oeste	5007695
9898	2583	Sete Quedas	5007703
9899	2583	Selviria	5007802
9900	2583	Sidrolandia	5007901
9901	2583	Sonora	5007935
9902	2583	Tacuru	5007950
9903	2583	Taquarussu	5007976
9904	2583	Terenos	5008008
9905	2583	Tres Lagoas	5008305
9906	2583	Vicentina	5008404
9907	2582	Acorizal	5100102
9908	2582	Agua Boa	5100201
9909	2582	Alta Floresta	5100250
9910	2582	Alto Araguaia	5100300
9911	2582	Alto Boa Vista	5100359
9912	2582	Alto Garcas	5100409
9913	2582	Alto Paraguai	5100508
9914	2582	Alto Taquari	5100607
9915	2582	Apiacas	5100805
9916	2582	Araguaiana	5101001
9917	2582	Araguainha	5101209
9918	2582	Araputanga	5101258
9919	2582	Arenapolis	5101308
9920	2582	Aripuana	5101407
9921	2582	Barao de Melgaco	5101605
9922	2582	Barra do Bugres	5101704
9923	2582	Barra do Garcas	5101803
9924	2582	BOM JESUS DO ARAGUAIA	5101852
9925	2582	Brasnorte	5101902
9926	2582	Caceres	5102504
9927	2582	Campinapolis	5102603
9928	2582	Campo Novo do Parecis	5102637
9929	2582	Campo Verde	5102678
9930	2582	Campos de Julio	5102686
9931	2582	Canabrava do Norte	5102694
9932	2582	Canarana	5102702
9933	2582	Carlinda	5102793
9934	2582	Castanheira	5102850
9935	2582	Chapada dos Guimaraes	5103007
9936	2582	Claudia	5103056
9937	2582	Cocalinho	5103106
9938	2582	Colider	5103205
9939	2582	COLNIZA	5103254
9940	2582	Comodoro	5103304
9941	2582	Confresa	5103353
9942	2582	CONQUISTA DOESTE	5103361
9943	2582	Cotriguacu	5103379
9944	2582	Cuiaba	5103403
9945	2582	CUVERLANDIA	5103437
9946	2582	Denise	5103452
9947	2582	Diamantino	5103502
9948	2582	Dom Aquino	5103601
9949	2582	Feliz Natal	5103700
9950	2582	Figueiropolis D'Oeste	5103809
9951	2582	Gaucha do Norte	5103858
9952	2582	General Carneiro	5103908
9953	2582	Gloria D'Oeste	5103957
9954	2582	Guaranta do Norte	5104104
9955	2582	Guiratinga	5104203
9956	2582	Indiavai	5104500
9957	2582	IPIRANGA DO NORTE	5104526
9958	2582	ITANHANGA	5104542
9959	2582	Itauba	5104559
9960	2582	Itiquira	5104609
9961	2582	Jaciara	5104807
9962	2582	Jangada	5104906
9963	2582	Jauru	5105002
9964	2582	Juara	5105101
9965	2582	Juina	5105150
9966	2582	Juruena	5105176
9967	2582	Juscimeira	5105200
9968	2582	Lambari D'Oeste	5105234
9969	2582	Lucas do Rio Verde	5105259
9970	2582	Luciara	5105309
9971	2582	Vila Bela da Santissima Trinda	5105507
9972	2582	Marcelandia	5105580
9973	2582	Matupa	5105606
9974	2582	Mirassol d'Oeste	5105622
9975	2582	Nobres	5105903
9976	2582	Nortelandia	5106000
9977	2582	Nossa Senhora do Livramento	5106109
9978	2582	Nova Bandeirantes	5106158
9979	2582	NOVA NAZARE	5106174
9980	2582	Nova Lacerda	5106182
9981	2582	NOVA SANTA HELENA	5106190
9982	2582	Nova Brasilandia	5106208
9983	2582	Nova Canaa do Norte	5106216
9984	2582	Nova Mutum	5106224
9985	2582	Nova Olimpia	5106232
9986	2582	Nova Ubirata	5106240
9987	2582	Nova Xavantina	5106257
9988	2582	Novo Mundo	5106265
9989	2582	Novo Horizonte do Norte	5106273
9990	2582	Novo Sao Joaquim	5106281
9991	2582	Paranaita	5106299
9992	2582	Paranatinga	5106307
9993	2582	NOVO SANTO ANTONIO	5106315
9994	2582	Pedra Preta	5106372
9995	2582	Peixoto de Azevedo	5106422
9996	2582	Planalto da Serra	5106455
9997	2582	Pocone	5106505
9998	2582	Pontal do Araguaia	5106653
9999	2582	Ponte Branca	5106703
10000	2582	Pontes e Lacerda	5106752
10001	2582	Porto Alegre do Norte	5106778
10002	2582	Porto dos Gauchos	5106802
10003	2582	Porto Esperidiao	5106828
10004	2582	Porto Estrela	5106851
10005	2582	Poxoreo	5107008
10006	2582	Primavera do Leste	5107040
10007	2582	Querencia	5107065
10008	2582	Sao Jose dos Quatro Marcos	5107107
10009	2582	Reserva do Cabacal	5107156
10010	2582	Ribeirao Cascalheira	5107180
10011	2582	Ribeiraozinho	5107198
10012	2582	Rio Branco	5107206
10013	2582	Santa Carmem	5107248
10014	2582	Santo Afonso	5107263
10015	2582	Sao Jose do Povo	5107297
10016	2582	Sao Jose do Rio Claro	5107305
10017	2582	Sao Jose do Xingu	5107354
10018	2582	Sao Pedro da Cipa	5107404
10019	2582	RONDOLANDIA	5107578
10020	2582	Rondonopolis	5107602
10021	2582	Rosario Oeste	5107701
10022	2582	SANTA CRUZ DO XINGU	5107743
10023	2582	Salto do Ceu	5107750
10024	2582	SANTA RITA DO TRIVELATO	5107768
10025	2582	Santa Terezinha	5107776
10026	2582	SANTO ANTONIO DO LESTE	5107792
10027	2582	Santo Antonio do Leverger	5107800
10028	2582	Sao Felix do Araguaia	5107859
10029	2582	Sapezal	5107875
10030	2582	SERRA NOVA DOURADA	5107883
10031	2582	Sinop	5107909
10032	2582	Sorriso	5107925
10033	2582	Tabapora	5107941
10034	2582	Tangara da Serra	5107958
10035	2582	Tapurah	5108006
10036	2582	Terra Nova do Norte	5108055
10037	2582	Tesouro	5108105
10038	2582	Torixoreu	5108204
10039	2582	Uniao do Sul	5108303
10040	2582	VALE DE SAO DOMINGOS	5108352
10041	2582	Varzea Grande	5108402
10042	2582	Vera	5108501
10043	2582	Vila Rica	5108600
10044	2582	Nova Guarita	5108808
10045	2582	Nova Marilandia	5108857
10046	2582	Nova Maringa	5108907
10047	2582	Nova Monte verde	5108956
10048	2580	Abadia de Goias	5200050
10049	2580	Abadiania	5200100
10050	2580	Acreuna	5200134
10051	2580	Adelandia	5200159
10052	2580	Agua Fria de Goias	5200175
10053	2580	Agua Limpa	5200209
10054	2580	Aguas Lindas de Goias	5200258
10055	2580	Alexania	5200308
10056	2580	Aloandia	5200506
10057	2580	Alto Horizonte	5200555
10058	2580	Alto Paraiso de Goias	5200605
10059	2580	Alvorada do Norte	5200803
10060	2580	Amaralina	5200829
10061	2580	Americano do Brasil	5200852
10062	2580	Amorinopolis	5200902
10063	2580	Anapolis	5201108
10064	2580	Anhanguera	5201207
10065	2580	Anicuns	5201306
10066	2580	Aparecida de Goiania	5201405
10067	2580	Aparecida do Rio Doce	5201454
10068	2580	Apore	5201504
10069	2580	Aracu	5201603
10070	2580	Aragarcas	5201702
10071	2580	Aragoiania	5201801
10072	2580	Araguapaz	5202155
10073	2580	Arenopolis	5202353
10074	2580	Aruana	5202502
10075	2580	Aurilandia	5202601
10076	2580	Avelinopolis	5202809
10077	2580	Baliza	5203104
10078	2580	Barro Alto	5203203
10079	2580	Bela Vista de Goias	5203302
10080	2580	Bom Jardim de Goias	5203401
10081	2580	Bom Jesus de Goias	5203500
10082	2580	Bonfinopolis	5203559
10083	2580	Bonopolis	5203575
10084	2580	Brazabrantes	5203609
10085	2580	Britania	5203807
10086	2580	Buriti Alegre	5203906
10087	2580	Buriti de Goias	5203939
10088	2580	Buritinopolis	5203962
10089	2580	Cabeceiras	5204003
10090	2580	Cachoeira Alta	5204102
10091	2580	Cachoeira de Goias	5204201
10092	2580	Cachoeira Dourada	5204250
10093	2580	Cacu	5204300
10094	2580	Caiaponia	5204409
10095	2580	Caldas Novas	5204508
10096	2580	Caldazinha	5204557
10097	2580	Campestre de Goias	5204607
10098	2580	Campinacu	5204656
10099	2580	Campinorte	5204706
10100	2580	Campo Alegre de Goias	5204805
10101	2580	CAMPO LIMPO DE GOIAS	5204854
10102	2580	Campos Belos	5204904
10103	2580	Campos Verdes	5204953
10104	2580	Carmo do Rio Verde	5205000
10105	2580	Castelandia	5205059
10106	2580	Catalao	5205109
10107	2580	Caturai	5205208
10108	2580	Cavalcante	5205307
10109	2580	Ceres	5205406
10110	2580	Cezarina	5205455
10111	2580	Chapadao do Ceu	5205471
10112	2580	Cidade Ocidental	5205497
10113	2580	Cocalzinho de Goias	5205513
10114	2580	Colinas do Sul	5205521
10115	2580	Corrego do Ouro	5205703
10116	2580	Corumba de Goias	5205802
10117	2580	Corumbaiba	5205901
10118	2580	Cristalina	5206206
10119	2580	Cristianopolis	5206305
10120	2580	Crixas	5206404
10121	2580	Crominia	5206503
10122	2580	Cumari	5206602
10123	2580	Damianopolis	5206701
10124	2580	Damolandia	5206800
10125	2580	Davinopolis	5206909
10126	2580	Diorama	5207105
10127	2580	Doverlandia	5207253
10128	2580	Edealina	5207352
10129	2580	Edeia	5207402
10130	2580	Estrela do Norte	5207501
10131	2580	Faina	5207535
10132	2580	Fazenda Nova	5207600
10133	2580	Firminopolis	5207808
10134	2580	Flores de Goias	5207907
10135	2580	Formosa	5208004
10136	2580	Formoso	5208103
10137	2580	GAMELEIRA DE GOIAS	5208152
10138	2580	Divinopolis de Goias	5208301
10139	2580	Goianapolis	5208400
10140	2580	Goiandira	5208509
10141	2580	Goianesia	5208608
10142	2580	Goiania	5208707
10143	2580	Goianira	5208806
10144	2580	Goias	5208905
10145	2580	Goiatuba	5209101
10146	2580	Gouvelandia	5209150
10147	2580	Guapo	5209200
10148	2580	Guaraita	5209291
10149	2580	Guarani de Goias	5209408
10150	2580	Guarinos	5209457
10151	2580	Heitorai	5209606
10152	2580	Hidrolandia	5209705
10153	2580	Hidrolina	5209804
10154	2580	Iaciara	5209903
10155	2580	Inaciolandia	5209937
10156	2580	Indiara	5209952
10157	2580	Inhumas	5210000
10158	2580	Ipameri	5210109
10159	2580	IPIRANGA DE GOIAS	5210158
10160	2580	Ipora	5210208
10161	2580	Israelandia	5210307
10162	2580	Itaberai	5210406
10163	2580	Itaguari	5210562
10164	2580	Itaguaru	5210604
10165	2580	Itaja	5210802
10166	2580	Itapaci	5210901
10167	2580	Itapirapua	5211008
10168	2580	Itapuranga	5211206
10169	2580	Itaruma	5211305
10170	2580	Itaucu	5211404
10171	2580	Itumbiara	5211503
10172	2580	Ivolandia	5211602
10173	2580	Jandaia	5211701
10174	2580	Jaragua	5211800
10175	2580	Jatai	5211909
10176	2580	Jaupaci	5212006
10177	2580	Jesupolis	5212055
10178	2580	Joviania	5212105
10179	2580	Jussara	5212204
10180	2580	LAGOA SANTA	5212253
10181	2580	Leopoldo de Bulhoes	5212303
10182	2580	Luziania	5212501
10183	2580	Mairipotaba	5212600
10184	2580	Mambai	5212709
10185	2580	Mara Rosa	5212808
10186	2580	Marzagao	5212907
10187	2580	Matrincha	5212956
10188	2580	Maurilandia	5213004
10189	2580	Mimoso de Goias	5213053
10190	2580	Minacu	5213087
10191	2580	Mineiros	5213103
10192	2580	Moipora	5213400
10193	2580	Monte Alegre de Goias	5213509
10194	2580	Montes Claros de Goias	5213707
10195	2580	Montividiu	5213756
10196	2580	Montividiu do Norte	5213772
10197	2580	Morrinhos	5213806
10198	2580	Morro Agudo de Goias	5213855
10199	2580	Mossamedes	5213905
10200	2580	Mozarlandia	5214002
10201	2580	Mundo Novo	5214051
10202	2580	Mutunopolis	5214101
10203	2580	Nazario	5214408
10204	2580	Neropolis	5214507
10205	2580	Niquelandia	5214606
10206	2580	Nova America	5214705
10207	2580	Nova Aurora	5214804
10208	2580	Nova Crixas	5214838
10209	2580	Nova Gloria	5214861
10210	2580	Nova Iguacu de Goias	5214879
10211	2580	Nova Roma	5214903
10212	2580	Nova Veneza	5215009
10213	2580	Novo Brasil	5215207
10214	2580	Novo Gama	5215231
10215	2580	Novo Planalto	5215256
10216	2580	Orizona	5215306
10217	2580	Ouro Verde de Goias	5215405
10218	2580	Ouvidor	5215504
10219	2580	Padre Bernardo	5215603
10220	2580	Palestina de Goias	5215652
10221	2580	Palmeiras de Goias	5215702
10222	2580	Palmelo	5215801
10223	2580	Palminopolis	5215900
10224	2580	Panama	5216007
10225	2580	Paranaiguara	5216304
10226	2580	Parauna	5216403
10227	2580	Perolandia	5216452
10228	2580	Petrolina de Goias	5216809
10229	2580	Pilar de Goias	5216908
10230	2580	Piracanjuba	5217104
10231	2580	Piranhas	5217203
10232	2580	Pirenopolis	5217302
10233	2580	Pires do Rio	5217401
10234	2580	Planaltina	5217609
10235	2580	Pontalina	5217708
10236	2580	Porangatu	5218003
10237	2580	Porteirao	5218052
10238	2580	Portelandia	5218102
10239	2580	Posse	5218300
10240	2580	Professor Jamil	5218391
10241	2580	Quirinopolis	5218508
10242	2580	Rialma	5218607
10243	2580	Rianapolis	5218706
10244	2580	Rio Quente	5218789
10245	2580	Rio Verde	5218805
10246	2580	Rubiataba	5218904
10247	2580	Sanclerlandia	5219001
10248	2580	Santa Barbara de Goias	5219100
10249	2580	Santa Cruz de Goias	5219209
10250	2580	Santa Fe de Goias	5219258
10251	2580	Santa Helena de Goias	5219308
10252	2580	Santa Isabel	5219357
10253	2580	Santa Rita do Araguaia	5219407
10254	2580	Santa Rita do Novo Destino	5219456
10255	2580	Santa Rosa de Goias	5219506
10256	2580	Santa Tereza de Goias	5219605
10257	2580	Santa Terezinha de Goias	5219704
10258	2580	Santo Antonio da Barra	5219712
10259	2580	Santo Antonio de Goias	5219738
10260	2580	Santo Antonio do Descoberto	5219753
10261	2580	Sao Domingos	5219803
10262	2580	Sao Francisco de Goias	5219902
10263	2580	Sao Joao d'Alianca	5220009
10264	2580	Sao Joao da Parauna	5220058
10265	2580	Sao Luis de Montes Belos	5220108
10266	2580	Sao Luiz do Norte	5220157
10267	2580	Sao Miguel do Araguaia	5220207
10268	2580	Sao Miguel do Passa Quatro	5220264
10269	2580	Sao Patricio	5220280
10270	2580	Sao Simao	5220405
10271	2580	Senador Canedo	5220454
10272	2580	Serranopolis	5220504
10273	2580	Silvania	5220603
10274	2580	Simolandia	5220686
10275	2580	Sitio d'Abadia	5220702
10276	2580	Taquaral de Goias	5221007
10277	2580	Teresina de Goias	5221080
10278	2580	Terezopolis de Goias	5221197
10279	2580	Tres Ranchos	5221304
10280	2580	Trindade	5221403
10281	2580	Trombas	5221452
10282	2580	Turvania	5221502
10283	2580	Turvelandia	5221551
10284	2580	Uirapuru	5221577
10285	2580	Uruacu	5221601
10286	2580	Uruana	5221700
10287	2580	Urutai	5221809
10288	2580	Valparaiso de Goias	5221858
10289	2580	Varjao	5221908
10290	2580	Vianopolis	5222005
10291	2580	Vicentinopolis	5222054
10292	2580	Vila Boa	5222203
10293	2580	Vila Propicio	5222302
10294	2578	Brasilia	5300108
1	2	Não Informado	0
\.


--
-- TOC entry 3157 (class 0 OID 0)
-- Dependencies: 266
-- Name: municipio_idmunicipio_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('municipio_idmunicipio_seq', 7, true);


--
-- TOC entry 2890 (class 0 OID 114917)
-- Dependencies: 269 2922
-- Data for Name: nivelensino; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY nivelensino (idnivelensino, nomenivelensino) FROM stdin;
3	Ensino Fundamental Inicial\n
4	Ensino Fundamental Final\n
5	Ensino Médio\n
6	Educação Infantil\n
7	Educação Profissional\n
8	Ensino Superior \n
11	Educação Escolar Indígena
12	Educação do Campo\n
13	Educação Especial\n
9	Educação de Jovens e Adultos 2
10	Educação de Jovens e Adultos 1
\.


--
-- TOC entry 3158 (class 0 OID 0)
-- Dependencies: 268
-- Name: nivelensino_idnivelensino_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('nivelensino_idnivelensino_seq', 13, true);


--
-- TOC entry 2891 (class 0 OID 114925)
-- Dependencies: 271 2922
-- Data for Name: redesocial; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY redesocial (idredesocial, site, rede) FROM stdin;
2	facebook.com	facebook
3	orkut.com	orkut
5	9gag.com	9gag
6	bebo.com	bebo
7	badoo.com	badoo
8	behance	behance
9	allegro	allegro
10	blip	blip
11	blogger	blogger
12	chceto	chceto
13	chomikuj	chomikuj
14	delicious	delicious
15	deviantart	deviantart
16	digart	digart
17	digg	digg
18	dribbble	dribbble
19	dropbox	dropbox
20	etsy	etsy
21	feedburner	feedburner
22	ffffound	ffffound
23	filmaster	filmaster
24	filmweb	filmweb
25	flaker	flaker
26	flickr	flickr
27	flixter	flixter
28	formspring	formspring
29	foursquare	foursquare
30	friendfeed	friendfeed
31	getglue	getglue
32	github	github
33	goldenline	goldenline
34	plus.google	google+
35	googlereader	googlereader
36	grono	grono
37	identica	identica
38	imdb	imdb
39	imgur	imgur
40	instagram	instagram
41	kickstarter	kickstarter
42	lastfm	lastfm
43	linkedin	linkedin
44	livejournal	livejournal
45	lookbook	lookbook
46	lubimyczytac	lubimyczytac
47	meetup	meetup
48	miso	miso
49	myguidie	myguidie
50	myspace	myspace
51	nakanapie	nakanapie
52	netvibes	netvibes
53	networkedblogs	networkedblogs
54	nk.pl	nk
55	pakamera	pakamera
56	picasa	picasa
57	pinger	pinger
58	pinterest	pinterest
59	posterous	posterous
60	profeo	profeo
61	quora	quora
62	reddit	reddit
63	scribd	scribd
64	skype	skype
65	slideshare	slideshare
66	soundcloud	soundcloud
67	soup	soup
68	stumbleupon	stumbleupon
69	sympatia	sympatia
70	tripadvisor	tripadvisor
71	tuenti	tuenti
72	tumblr	tumblr
73	twitter.com	twitter
74	vimeo	vimeo
75	visualizeus	visualizeus
76	weheartit	weheartit
77	wordpress.com	wordpress
78	wordpress.org	wordpress
79	wykop	wykop
80	yahooanswers	yahooanswers
81	yahoo.com	yahoo
82	yelp	yelp
83	youtube.com	youtube
\.


--
-- TOC entry 3159 (class 0 OID 0)
-- Dependencies: 272
-- Name: redesocial_idredesocial_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('redesocial_idredesocial_seq', 1, false);


--
-- TOC entry 2894 (class 0 OID 114935)
-- Dependencies: 274 2922
-- Data for Name: serie; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY serie (idserie, nomeserie) FROM stdin;
1	1a Serie
\.


--
-- TOC entry 3160 (class 0 OID 0)
-- Dependencies: 273
-- Name: serie_idserie_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('serie_idserie_seq', 1, false);


--
-- TOC entry 2895 (class 0 OID 114939)
-- Dependencies: 275 2922
-- Data for Name: servidor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY servidor (idservidor, pathservidor) FROM stdin;
1	/dados/srv1
2	/dados/srv2
3	/dados/srv3
\.


--
-- TOC entry 3161 (class 0 OID 0)
-- Dependencies: 276
-- Name: servidor_idservidor_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('servidor_idservidor_seq', 3, true);


--
-- TOC entry 2898 (class 0 OID 114946)
-- Dependencies: 278 2922
-- Data for Name: tag; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tag (idtag, nometag, busca, dataatualizacao) FROM stdin;
\.


--
-- TOC entry 3162 (class 0 OID 0)
-- Dependencies: 277
-- Name: tag_idtag_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tag_idtag_seq', 9887, true);


--
-- TOC entry 2900 (class 0 OID 114952)
-- Dependencies: 280 2922
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY usuario (idusuario, idfavorito, idusuariotipo, idmunicipio, idescola, idserie, categoria, username, matricula, nomeusuario, datanascimento, sexo, telefone, endereco, numero, complemento, bairro, cep, cpf, rg, email, datacriacao, flativo, dataatualizacao, senha, emailpessoal) FROM stdin;
3	2	2	\N	\N	\N	s	administrador	\N	Administrador AEW	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	administrador@email.com	2016-06-06 10:37:50.11214	t	\N	d22324080edad379667fea1d73b435bc	administrador@email.com
4	3	3	\N	\N	\N	s	coordenador	\N	Coordenador AEW	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	coordenador@email.com	2016-06-06 10:41:37.983242	t	\N	8cdbe0f83e37e871cfdb0c24ad2cc3c0	coordenador@email.com
5	4	4	\N	\N	\N	s	editor	\N	Editor AEW	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	editor@email.com	2016-06-06 10:43:26.64645	t	\N	0b83147d789e4f0783278792ea91d8b2	editor@email.com
6	5	5	\N	\N	\N	s	colaborador	\N	Colaborador AEW	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	colaborador@email.com	2016-06-06 10:46:33.596588	t	\N	865efecfd428273ba53e2fae810080ab	colaborador@email.com
7	6	6	\N	\N	\N	s	amigodaescola	\N	Amigo da Escola	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	amigodaescola@email.com	2016-06-06 10:47:34.069214	t	\N	b6ca3b621f949431fb564062c7283ac8	amigodaescola@email.com
2	1	1	\N	\N	\N	s	admin	\N	Super Administrador AEW	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	admin@email.com	2016-06-06 10:32:33.529711	t	\N	254b77cb027d6587eb650a43e4e7bd09	admin@email.com
\.


--
-- TOC entry 3163 (class 0 OID 0)
-- Dependencies: 279
-- Name: usuario_idusuario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('usuario_idusuario_seq', 2665, true);


--
-- TOC entry 2902 (class 0 OID 114964)
-- Dependencies: 282 2922
-- Data for Name: usuarioagenda; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY usuarioagenda (idusuarioagenda, idusuario, datainicio, datafim, evento, link1, linktitulo1, link2, linktitulo2, link3, linktitulo3, mensagem, local, marcacao) FROM stdin;
\.


--
-- TOC entry 3164 (class 0 OID 0)
-- Dependencies: 281
-- Name: usuarioagenda_idusuarioagenda_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('usuarioagenda_idusuarioagenda_seq', 40, true);


--
-- TOC entry 2903 (class 0 OID 114972)
-- Dependencies: 283 2922
-- Data for Name: usuarioalbum; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY usuarioalbum (idusuario, datacriacao, idusuarioalbum, titulo) FROM stdin;
\.


--
-- TOC entry 3165 (class 0 OID 0)
-- Dependencies: 284
-- Name: usuarioalbum_idusuarioalbum_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('usuarioalbum_idusuarioalbum_seq', 90, true);


--
-- TOC entry 2905 (class 0 OID 114978)
-- Dependencies: 285 2922
-- Data for Name: usuarioalbumfoto; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY usuarioalbumfoto (idusuarioalbumfoto, idusuarioalbum, legenda, extensao, flperfil, datacriacao) FROM stdin;
\.


--
-- TOC entry 3166 (class 0 OID 0)
-- Dependencies: 286
-- Name: usuarioalbumfoto_idusuarioalbumfoto_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('usuarioalbumfoto_idusuarioalbumfoto_seq', 277, true);


--
-- TOC entry 2908 (class 0 OID 114986)
-- Dependencies: 288 2922
-- Data for Name: usuarioamigo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY usuarioamigo (idusuarioamigo, idusuario, idusuarioindicou, idusuarioaprovar, flaprovador, flespacoaberto, datacriacao) FROM stdin;
\.


--
-- TOC entry 3167 (class 0 OID 0)
-- Dependencies: 287
-- Name: usuarioamigo_idusuarioamigo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('usuarioamigo_idusuarioamigo_seq', 3, true);


--
-- TOC entry 2909 (class 0 OID 115002)
-- Dependencies: 289 2922
-- Data for Name: usuariocolega; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY usuariocolega (idusuario, idusuariocolega, flativocolega, datacriacao, idcolega, visto) FROM stdin;
\.


--
-- TOC entry 3168 (class 0 OID 0)
-- Dependencies: 290
-- Name: usuariocolega_idusuariocolega_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('usuariocolega_idusuariocolega_seq', 1, false);


--
-- TOC entry 2911 (class 0 OID 115010)
-- Dependencies: 291 2922
-- Data for Name: usuariocomponente; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY usuariocomponente (idusuario, idcomponentecurricular) FROM stdin;
\.


--
-- TOC entry 2912 (class 0 OID 115013)
-- Dependencies: 292 2922
-- Data for Name: usuariofoto; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY usuariofoto (idusuariofoto, extensao, idusuario) FROM stdin;
\.


--
-- TOC entry 3169 (class 0 OID 0)
-- Dependencies: 293
-- Name: usuariofoto_idusuariofoto_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('usuariofoto_idusuariofoto_seq', 542, true);


--
-- TOC entry 2915 (class 0 OID 115020)
-- Dependencies: 295 2922
-- Data for Name: usuariorecado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY usuariorecado (idusuariorecado, idusuario, idusuarioautor, recado, dataenvio, tiporecado, visto, idrecadorelacionado) FROM stdin;
\.


--
-- TOC entry 3170 (class 0 OID 0)
-- Dependencies: 294
-- Name: usuariorecado_idusuariorecado_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('usuariorecado_idusuariorecado_seq', 1212, true);


--
-- TOC entry 2916 (class 0 OID 115030)
-- Dependencies: 296 2922
-- Data for Name: usuarioredesocial; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY usuarioredesocial (idusuarioredesocial, idusuario, idredesocial, url) FROM stdin;
\.


--
-- TOC entry 3171 (class 0 OID 0)
-- Dependencies: 297
-- Name: usuarioredesocial_idusuarioredesocial_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('usuarioredesocial_idusuarioredesocial_seq', 88, true);


--
-- TOC entry 2918 (class 0 OID 115035)
-- Dependencies: 298 2922
-- Data for Name: usuariosobremimperfil; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY usuariosobremimperfil (idusuario, sobremim, cidadenatal, lattes, dataenvio) FROM stdin;
\.


--
-- TOC entry 2919 (class 0 OID 115041)
-- Dependencies: 299 2922
-- Data for Name: usuariotag; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY usuariotag (idusuario, idtag) FROM stdin;
\.


--
-- TOC entry 2921 (class 0 OID 115046)
-- Dependencies: 301 2922
-- Data for Name: usuariotipo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY usuariotipo (idusuariotipo, nomeusuariotipo, descricao) FROM stdin;
1	super administrador	\N
2	administrador	\N
3	coordenador	\N
4	editor	\N
5	colaborador	\N
6	amigo da escola	\N
\.


--
-- TOC entry 3172 (class 0 OID 0)
-- Dependencies: 300
-- Name: usuariotipo_idusuariotipo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('usuariotipo_idusuariotipo_seq', 6, true);


--
-- TOC entry 2371 (class 2606 OID 115317)
-- Dependencies: 165 165 2923
-- Name: acessibilidade_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY acessibilidade
    ADD CONSTRAINT acessibilidade_pkey PRIMARY KEY (idacessibilidade);


--
-- TOC entry 2373 (class 2606 OID 115319)
-- Dependencies: 167 167 2923
-- Name: agendacomentario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY agendacomentario
    ADD CONSTRAINT agendacomentario_pkey PRIMARY KEY (idagendacomentario);


--
-- TOC entry 2375 (class 2606 OID 115321)
-- Dependencies: 170 170 2923
-- Name: albumcomentario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY albumcomentario
    ADD CONSTRAINT albumcomentario_pkey PRIMARY KEY (idalbumcomentario);


--
-- TOC entry 2391 (class 2606 OID 115323)
-- Dependencies: 184 184 2923
-- Name: blogcomentario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY blogcomentario
    ADD CONSTRAINT blogcomentario_pkey PRIMARY KEY (idblogcomentario);


--
-- TOC entry 2397 (class 2606 OID 115325)
-- Dependencies: 186 186 2923
-- Name: canal_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY canal
    ADD CONSTRAINT canal_pkey PRIMARY KEY (idcanal);


--
-- TOC entry 2399 (class 2606 OID 115327)
-- Dependencies: 188 188 2923
-- Name: categoriacomponentecurricular_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY categoriacomponentecurricular
    ADD CONSTRAINT categoriacomponentecurricular_pkey PRIMARY KEY (idcategoriacomponentecurricular);


--
-- TOC entry 2402 (class 2606 OID 115329)
-- Dependencies: 190 190 2923
-- Name: chat_mensagens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY chatmensagens
    ADD CONSTRAINT chat_mensagens_pkey PRIMARY KEY (id);


--
-- TOC entry 2411 (class 2606 OID 115331)
-- Dependencies: 192 192 192 2923
-- Name: chat_mensagens_status_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY chatmensagensstatus
    ADD CONSTRAINT chat_mensagens_status_pkey PRIMARY KEY (id_de, id_para);


--
-- TOC entry 2417 (class 2606 OID 115333)
-- Dependencies: 195 195 2923
-- Name: componentecurricular_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY componentecurricular
    ADD CONSTRAINT componentecurricular_pkey PRIMARY KEY (idcomponentecurricular);


--
-- TOC entry 2419 (class 2606 OID 115335)
-- Dependencies: 196 196 2923
-- Name: componentecurriculartopico_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY componentecurriculartopico
    ADD CONSTRAINT componentecurriculartopico_pkey PRIMARY KEY (idcomponentecurriculartopico);


--
-- TOC entry 2428 (class 2606 OID 115337)
-- Dependencies: 201 201 2923
-- Name: comuagenda_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY comunidadeagenda
    ADD CONSTRAINT comuagenda_pkey PRIMARY KEY (idcomunidadeagenda);


--
-- TOC entry 2424 (class 2606 OID 115339)
-- Dependencies: 200 200 2923
-- Name: comunidade_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY comunidade
    ADD CONSTRAINT comunidade_pkey PRIMARY KEY (idcomunidade);


--
-- TOC entry 2430 (class 2606 OID 115341)
-- Dependencies: 203 203 2923
-- Name: comunidadealbum_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY comunidadealbum
    ADD CONSTRAINT comunidadealbum_pkey PRIMARY KEY (idcomunidadealbum);


--
-- TOC entry 2432 (class 2606 OID 115343)
-- Dependencies: 205 205 2923
-- Name: comunidadealbumfoto_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY comunidadealbumfoto
    ADD CONSTRAINT comunidadealbumfoto_pkey PRIMARY KEY (idcomunidadealbumfoto);


--
-- TOC entry 2434 (class 2606 OID 115345)
-- Dependencies: 207 207 2923
-- Name: comunidadeblog_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY comunidadeblog
    ADD CONSTRAINT comunidadeblog_pkey PRIMARY KEY (idcomunidadeblog);


--
-- TOC entry 2441 (class 2606 OID 115347)
-- Dependencies: 212 212 212 2923
-- Name: comunidadetag_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY comunidadetag
    ADD CONSTRAINT comunidadetag_pkey PRIMARY KEY (idcomunidade, idtag);


--
-- TOC entry 2443 (class 2606 OID 115349)
-- Dependencies: 214 214 2923
-- Name: comurelacionada_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY comurelacionada
    ADD CONSTRAINT comurelacionada_pkey PRIMARY KEY (idcomurelacionada);


--
-- TOC entry 2445 (class 2606 OID 115351)
-- Dependencies: 216 216 2923
-- Name: comutopico_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY comutopico
    ADD CONSTRAINT comutopico_pkey PRIMARY KEY (idcomutopico);


--
-- TOC entry 2447 (class 2606 OID 115353)
-- Dependencies: 218 218 2923
-- Name: comutopicomsg_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY comutopicomsg
    ADD CONSTRAINT comutopicomsg_pkey PRIMARY KEY (idcomutopicomsg);


--
-- TOC entry 2449 (class 2606 OID 115355)
-- Dependencies: 219 219 2923
-- Name: comuusuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY comuusuario
    ADD CONSTRAINT comuusuario_pkey PRIMARY KEY (idcomuusuario);


--
-- TOC entry 2451 (class 2606 OID 115357)
-- Dependencies: 222 222 2923
-- Name: comuvoto_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY comuvoto
    ADD CONSTRAINT comuvoto_pkey PRIMARY KEY (idcomuvoto);


--
-- TOC entry 2453 (class 2606 OID 115359)
-- Dependencies: 224 224 2923
-- Name: conteudodigital_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY conteudodigital
    ADD CONSTRAINT conteudodigital_pkey PRIMARY KEY (idconteudodigital);


--
-- TOC entry 2459 (class 2606 OID 115361)
-- Dependencies: 225 225 2923
-- Name: conteudodigitalcategoria_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY conteudodigitalcategoria
    ADD CONSTRAINT conteudodigitalcategoria_pkey PRIMARY KEY (idconteudodigitalcategoria);


--
-- TOC entry 2462 (class 2606 OID 115363)
-- Dependencies: 228 228 2923
-- Name: conteudodigitalcomentario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY conteudodigitalcomentario
    ADD CONSTRAINT conteudodigitalcomentario_pkey PRIMARY KEY (idconteudodigitalcomentario);


--
-- TOC entry 2464 (class 2606 OID 115365)
-- Dependencies: 229 229 229 2923
-- Name: conteudodigitalcomponente_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY conteudodigitalcomponente
    ADD CONSTRAINT conteudodigitalcomponente_pkey PRIMARY KEY (idconteudodigital, idcomponentecurricular);


--
-- TOC entry 2466 (class 2606 OID 115367)
-- Dependencies: 230 230 230 2923
-- Name: conteudodigitalfavorito_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY conteudodigitalfavorito
    ADD CONSTRAINT conteudodigitalfavorito_pkey PRIMARY KEY (idconteudodigital, idfavorito);


--
-- TOC entry 2468 (class 2606 OID 115369)
-- Dependencies: 231 231 231 2923
-- Name: conteudodigitalrelacionado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY conteudodigitalrelacionado
    ADD CONSTRAINT conteudodigitalrelacionado_pkey PRIMARY KEY (idconteudodigital, idconteudodigitalrelacionado);


--
-- TOC entry 2470 (class 2606 OID 115371)
-- Dependencies: 232 232 232 2923
-- Name: conteudodigitaltag_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY conteudodigitaltag
    ADD CONSTRAINT conteudodigitaltag_pkey PRIMARY KEY (idconteudodigital, idtag);


--
-- TOC entry 2472 (class 2606 OID 115373)
-- Dependencies: 234 234 2923
-- Name: conteudodigitalvoto_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY conteudodigitalvoto
    ADD CONSTRAINT conteudodigitalvoto_pkey PRIMARY KEY (idconteudodigitalvoto);


--
-- TOC entry 2474 (class 2606 OID 115375)
-- Dependencies: 235 235 2923
-- Name: conteudolicenca_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY conteudolicenca
    ADD CONSTRAINT conteudolicenca_pkey PRIMARY KEY (idconteudolicenca);


--
-- TOC entry 2476 (class 2606 OID 115377)
-- Dependencies: 238 238 2923
-- Name: conteudotipo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY conteudotipo
    ADD CONSTRAINT conteudotipo_pkey PRIMARY KEY (idconteudotipo);


--
-- TOC entry 2478 (class 2606 OID 115379)
-- Dependencies: 240 240 2923
-- Name: denuncia_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY denuncia
    ADD CONSTRAINT denuncia_pkey PRIMARY KEY (iddenuncia);


--
-- TOC entry 2480 (class 2606 OID 115381)
-- Dependencies: 241 241 2923
-- Name: dispositivo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY dispositivo
    ADD CONSTRAINT dispositivo_pkey PRIMARY KEY (iddispositivo);


--
-- TOC entry 2482 (class 2606 OID 115383)
-- Dependencies: 244 244 2923
-- Name: enquete_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY enquete
    ADD CONSTRAINT enquete_pkey PRIMARY KEY (idenquete);


--
-- TOC entry 2484 (class 2606 OID 115385)
-- Dependencies: 246 246 2923
-- Name: enqueteopcao_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY enqueteopcao
    ADD CONSTRAINT enqueteopcao_pkey PRIMARY KEY (idenqueteopcao);


--
-- TOC entry 2486 (class 2606 OID 115387)
-- Dependencies: 248 248 2923
-- Name: enqueteopcaoresposta_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY enqueteopcaoresposta
    ADD CONSTRAINT enqueteopcaoresposta_pkey PRIMARY KEY (idenqueteopcaoresposta);


--
-- TOC entry 2488 (class 2606 OID 115389)
-- Dependencies: 250 250 2923
-- Name: escola_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY escola
    ADD CONSTRAINT escola_pkey PRIMARY KEY (idescola);


--
-- TOC entry 2490 (class 2606 OID 115391)
-- Dependencies: 252 252 2923
-- Name: estado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY estado
    ADD CONSTRAINT estado_pkey PRIMARY KEY (idestado);


--
-- TOC entry 2492 (class 2606 OID 115393)
-- Dependencies: 254 254 2923
-- Name: favorito_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY favorito
    ADD CONSTRAINT favorito_pkey PRIMARY KEY (idfavorito);


--
-- TOC entry 2494 (class 2606 OID 115395)
-- Dependencies: 255 255 2923
-- Name: feedcontagem_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY feedcontagem
    ADD CONSTRAINT feedcontagem_pkey PRIMARY KEY (idfeedcontagem);


--
-- TOC entry 2500 (class 2606 OID 115397)
-- Dependencies: 257 257 2923
-- Name: feeddetalhe_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY feeddetalhe
    ADD CONSTRAINT feeddetalhe_pkey PRIMARY KEY (id);


--
-- TOC entry 2512 (class 2606 OID 115399)
-- Dependencies: 264 264 2923
-- Name: formato_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY formato
    ADD CONSTRAINT formato_pkey PRIMARY KEY (idformato);


--
-- TOC entry 2507 (class 2606 OID 115401)
-- Dependencies: 259 259 2923
-- Name: id_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY feedtabela
    ADD CONSTRAINT id_pkey PRIMARY KEY (id);


--
-- TOC entry 2509 (class 2606 OID 115403)
-- Dependencies: 261 261 2923
-- Name: idtipo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY feedtipo
    ADD CONSTRAINT idtipo_pkey PRIMARY KEY (id);


--
-- TOC entry 2514 (class 2606 OID 115405)
-- Dependencies: 265 265 265 265 2923
-- Name: marcacaoagenda_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY marcacaoagenda
    ADD CONSTRAINT marcacaoagenda_pkey PRIMARY KEY (idagenda, idusuario, tipo);


--
-- TOC entry 2516 (class 2606 OID 115407)
-- Dependencies: 267 267 2923
-- Name: municipio_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY municipio
    ADD CONSTRAINT municipio_pkey PRIMARY KEY (idmunicipio);


--
-- TOC entry 2518 (class 2606 OID 115409)
-- Dependencies: 269 269 2923
-- Name: nivelensino_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY nivelensino
    ADD CONSTRAINT nivelensino_pkey PRIMARY KEY (idnivelensino);


--
-- TOC entry 2437 (class 2606 OID 115411)
-- Dependencies: 209 209 2923
-- Name: pk_comunidadefoto; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY comunidadefoto
    ADD CONSTRAINT pk_comunidadefoto PRIMARY KEY (idcomunidadefoto);


--
-- TOC entry 2385 (class 2606 OID 115413)
-- Dependencies: 178 178 2923
-- Name: pk_sistemacomentario; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ambientedeapoiocomentario
    ADD CONSTRAINT pk_sistemacomentario PRIMARY KEY (idambientedeapoiocomentario);


--
-- TOC entry 2543 (class 2606 OID 115415)
-- Dependencies: 289 289 2923
-- Name: pk_usuariocolega; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuariocolega
    ADD CONSTRAINT pk_usuariocolega PRIMARY KEY (idusuariocolega);


--
-- TOC entry 2548 (class 2606 OID 115417)
-- Dependencies: 292 292 2923
-- Name: pk_usuariofoto; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuariofoto
    ADD CONSTRAINT pk_usuariofoto PRIMARY KEY (idusuariofoto);


--
-- TOC entry 2520 (class 2606 OID 115419)
-- Dependencies: 271 271 2923
-- Name: redesocial_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY redesocial
    ADD CONSTRAINT redesocial_pkey PRIMARY KEY (idredesocial);


--
-- TOC entry 2522 (class 2606 OID 115421)
-- Dependencies: 274 274 2923
-- Name: serie_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY serie
    ADD CONSTRAINT serie_pkey PRIMARY KEY (idserie);


--
-- TOC entry 2524 (class 2606 OID 115423)
-- Dependencies: 275 275 2923
-- Name: servidor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY servidor
    ADD CONSTRAINT servidor_pkey PRIMARY KEY (idservidor);


--
-- TOC entry 2381 (class 2606 OID 115425)
-- Dependencies: 174 174 2923
-- Name: sistema_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ambientedeapoio
    ADD CONSTRAINT sistema_pkey PRIMARY KEY (idambientedeapoio);


--
-- TOC entry 2387 (class 2606 OID 115427)
-- Dependencies: 179 179 179 2923
-- Name: sistemafavorito_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ambientedeapoiofavorito
    ADD CONSTRAINT sistemafavorito_pkey PRIMARY KEY (idambientedeapoio, idfavorito);


--
-- TOC entry 2389 (class 2606 OID 115429)
-- Dependencies: 180 180 180 2923
-- Name: sistematag_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ambientedeapoiotag
    ADD CONSTRAINT sistematag_pkey PRIMARY KEY (idambientedeapoio, idtag);


--
-- TOC entry 2439 (class 2606 OID 115431)
-- Dependencies: 211 211 211 2923
-- Name: solicitacaocomunidade_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY comunidadesugerida
    ADD CONSTRAINT solicitacaocomunidade_pkey PRIMARY KEY (idusuario, idcomunidade);


--
-- TOC entry 2527 (class 2606 OID 115433)
-- Dependencies: 278 278 2923
-- Name: tag_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tag
    ADD CONSTRAINT tag_pkey PRIMARY KEY (idtag);


--
-- TOC entry 2383 (class 2606 OID 115435)
-- Dependencies: 176 176 2923
-- Name: tiposistema_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ambientedeapoiocategoria
    ADD CONSTRAINT tiposistema_pkey PRIMARY KEY (idambientedeapoiocategoria);


--
-- TOC entry 2533 (class 2606 OID 115437)
-- Dependencies: 280 280 2923
-- Name: usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT usuario_pkey PRIMARY KEY (idusuario);


--
-- TOC entry 2535 (class 2606 OID 115439)
-- Dependencies: 282 282 2923
-- Name: usuarioagenda_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuarioagenda
    ADD CONSTRAINT usuarioagenda_pkey PRIMARY KEY (idusuarioagenda);


--
-- TOC entry 2537 (class 2606 OID 115441)
-- Dependencies: 283 283 2923
-- Name: usuarioalbum_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuarioalbum
    ADD CONSTRAINT usuarioalbum_pkey PRIMARY KEY (idusuarioalbum);


--
-- TOC entry 2539 (class 2606 OID 115443)
-- Dependencies: 285 285 2923
-- Name: usuarioalbumfoto_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuarioalbumfoto
    ADD CONSTRAINT usuarioalbumfoto_pkey PRIMARY KEY (idusuarioalbumfoto);


--
-- TOC entry 2541 (class 2606 OID 115445)
-- Dependencies: 288 288 2923
-- Name: usuarioamigo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuarioamigo
    ADD CONSTRAINT usuarioamigo_pkey PRIMARY KEY (idusuarioamigo);


--
-- TOC entry 2545 (class 2606 OID 115449)
-- Dependencies: 291 291 291 2923
-- Name: usuariocomponente_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuariocomponente
    ADD CONSTRAINT usuariocomponente_pkey PRIMARY KEY (idusuario, idcomponentecurricular);


--
-- TOC entry 2551 (class 2606 OID 115451)
-- Dependencies: 295 295 2923
-- Name: usuariorecado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuariorecado
    ADD CONSTRAINT usuariorecado_pkey PRIMARY KEY (idusuariorecado);


--
-- TOC entry 2553 (class 2606 OID 115453)
-- Dependencies: 296 296 2923
-- Name: usuarioredesocial_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuarioredesocial
    ADD CONSTRAINT usuarioredesocial_pkey PRIMARY KEY (idusuarioredesocial);


--
-- TOC entry 2555 (class 2606 OID 115455)
-- Dependencies: 298 298 2923
-- Name: usuariosobremimperfil_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuariosobremimperfil
    ADD CONSTRAINT usuariosobremimperfil_pkey PRIMARY KEY (idusuario);


--
-- TOC entry 2557 (class 2606 OID 115457)
-- Dependencies: 299 299 299 2923
-- Name: usuariotag_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuariotag
    ADD CONSTRAINT usuariotag_pkey PRIMARY KEY (idusuario, idtag);


--
-- TOC entry 2559 (class 2606 OID 115459)
-- Dependencies: 301 301 2923
-- Name: usuariotipo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuariotipo
    ADD CONSTRAINT usuariotipo_pkey PRIMARY KEY (idusuariotipo);


--
-- TOC entry 2376 (class 1259 OID 115460)
-- Dependencies: 170 170 170 2923
-- Name: albumtipocomentario_albumcomentario; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX albumtipocomentario_albumcomentario ON albumcomentario USING btree (idusuarioalbumfoto NULLS FIRST, tipocomentario NULLS FIRST, tipoalbum NULLS FIRST);


--
-- TOC entry 2392 (class 1259 OID 115461)
-- Dependencies: 184 184 2923
-- Name: blogtipo_blogcomentario; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX blogtipo_blogcomentario ON blogcomentario USING btree (idblog NULLS FIRST, tipo NULLS FIRST);


--
-- TOC entry 2498 (class 1259 OID 115462)
-- Dependencies: 257 2923
-- Name: datacriacao; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX datacriacao ON feeddetalhe USING btree (datacriacao DESC NULLS LAST);


--
-- TOC entry 2420 (class 1259 OID 115463)
-- Dependencies: 196 2923
-- Name: fki_componentecurricular_idcomponentecurricular; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX fki_componentecurricular_idcomponentecurricular ON componentecurriculartopico USING btree (idcomponentecurricular);


--
-- TOC entry 2421 (class 1259 OID 115464)
-- Dependencies: 196 2923
-- Name: fki_componentecurriculartopico_idcomponentecurricular; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX fki_componentecurriculartopico_idcomponentecurricular ON componentecurriculartopico USING btree (idcomponentecurricular);


--
-- TOC entry 2422 (class 1259 OID 115465)
-- Dependencies: 196 2923
-- Name: fki_componentecurriculartopico_idcomponentecurricularpai; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX fki_componentecurriculartopico_idcomponentecurricularpai ON componentecurriculartopico USING btree (idcomponentecurriculartopicopai DESC);


--
-- TOC entry 2435 (class 1259 OID 115466)
-- Dependencies: 209 2923
-- Name: fki_comunidadefoto_idcomunidade; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX fki_comunidadefoto_idcomunidade ON comunidadefoto USING btree (idcomunidade);


--
-- TOC entry 2460 (class 1259 OID 115467)
-- Dependencies: 225 2923
-- Name: fki_conteudodigitalcategoria_idconteudodigitalcategoriapai; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX fki_conteudodigitalcategoria_idconteudodigitalcategoriapai ON conteudodigitalcategoria USING btree (idconteudodigitalcategoriapai DESC);


--
-- TOC entry 2510 (class 1259 OID 115468)
-- Dependencies: 264 2923
-- Name: fki_formato_idconteudotipo; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX fki_formato_idconteudotipo ON formato USING btree (idconteudotipo);


--
-- TOC entry 2546 (class 1259 OID 115469)
-- Dependencies: 292 2923
-- Name: fki_usuariofoto_idusuario; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX fki_usuariofoto_idusuario ON usuariofoto USING btree (idusuario);


--
-- TOC entry 2549 (class 1259 OID 115470)
-- Dependencies: 295 2923
-- Name: fki_usuariorecado_idusuariorecado; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX fki_usuariorecado_idusuariorecado ON usuariorecado USING btree (idrecadorelacionado);


--
-- TOC entry 2495 (class 1259 OID 115471)
-- Dependencies: 255 2923
-- Name: flacesso_feedcontagem; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX flacesso_feedcontagem ON feedcontagem USING btree (flacesso NULLS FIRST);


--
-- TOC entry 2393 (class 1259 OID 115472)
-- Dependencies: 184 2923
-- Name: idblog_blogcomentario; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idblog_blogcomentario ON blogcomentario USING btree (idblog NULLS FIRST);


--
-- TOC entry 2425 (class 1259 OID 115473)
-- Dependencies: 200 2923
-- Name: idfavorito_comunidade; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idfavorito_comunidade ON comunidade USING btree (idfavorito NULLS FIRST);


--
-- TOC entry 2501 (class 1259 OID 115474)
-- Dependencies: 257 2923
-- Name: idfeedmensagem; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idfeedmensagem ON feeddetalhe USING btree (idfeedmensagem NULLS FIRST);


--
-- TOC entry 2502 (class 1259 OID 115475)
-- Dependencies: 257 2923
-- Name: idfeedtabela; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idfeedtabela ON feeddetalhe USING btree (idfeedtabela NULLS FIRST);


--
-- TOC entry 2503 (class 1259 OID 115476)
-- Dependencies: 257 2923
-- Name: idregistrotabela; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idregistrotabela ON feeddetalhe USING btree (idregistrotabela NULLS FIRST);


--
-- TOC entry 2394 (class 1259 OID 115477)
-- Dependencies: 184 2923
-- Name: idusuario_blogcomentario; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idusuario_blogcomentario ON blogcomentario USING btree (idusuario NULLS FIRST);


--
-- TOC entry 2426 (class 1259 OID 115478)
-- Dependencies: 200 2923
-- Name: idusuario_comunidade; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idusuario_comunidade ON comunidade USING btree (idusuario NULLS FIRST);


--
-- TOC entry 2496 (class 1259 OID 115479)
-- Dependencies: 255 255 2923
-- Name: idusuario_dataacesso_feedcontagem; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idusuario_dataacesso_feedcontagem ON feedcontagem USING btree (idusuario NULLS FIRST, dataacesso DESC);


--
-- TOC entry 2497 (class 1259 OID 115480)
-- Dependencies: 255 2923
-- Name: idusuario_feedcontagem; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX idusuario_feedcontagem ON feedcontagem USING btree (idusuario NULLS FIRST);


--
-- TOC entry 2377 (class 1259 OID 115481)
-- Dependencies: 170 2923
-- Name: idusuarioalbumfoto_albumcomentario; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idusuarioalbumfoto_albumcomentario ON albumcomentario USING btree (idusuarioalbumfoto NULLS FIRST);


--
-- TOC entry 2504 (class 1259 OID 115482)
-- Dependencies: 257 2923
-- Name: idusuariodestinatario; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idusuariodestinatario ON feeddetalhe USING btree (idusuariodestinatario NULLS FIRST);


--
-- TOC entry 2505 (class 1259 OID 115483)
-- Dependencies: 257 2923
-- Name: idusuarioremetente; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idusuarioremetente ON feeddetalhe USING btree (idusuarioremetente NULLS FIRST);


--
-- TOC entry 2403 (class 1259 OID 115484)
-- Dependencies: 190 2923
-- Name: idx_chatmensagens01; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_chatmensagens01 ON chatmensagens USING btree (id_de);


--
-- TOC entry 2404 (class 1259 OID 115485)
-- Dependencies: 190 2923
-- Name: idx_chatmensagens02; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_chatmensagens02 ON chatmensagens USING btree (id_para);


--
-- TOC entry 2405 (class 1259 OID 115486)
-- Dependencies: 190 190 190 2923
-- Name: idx_chatmensagens03; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_chatmensagens03 ON chatmensagens USING btree (id_de, id_para, data);


--
-- TOC entry 2406 (class 1259 OID 115487)
-- Dependencies: 190 190 190 2923
-- Name: idx_chatmensagens04; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_chatmensagens04 ON chatmensagens USING btree (id_para, id_de, data);


--
-- TOC entry 2407 (class 1259 OID 115488)
-- Dependencies: 190 2923
-- Name: idx_chatmensagens05; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_chatmensagens05 ON chatmensagens USING btree (data);


--
-- TOC entry 2408 (class 1259 OID 115489)
-- Dependencies: 190 190 2923
-- Name: idx_chatmensagens06; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_chatmensagens06 ON chatmensagens USING btree (id_de NULLS FIRST, lido NULLS FIRST);


--
-- TOC entry 2409 (class 1259 OID 115490)
-- Dependencies: 190 190 2923
-- Name: idx_chatmensagens07; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_chatmensagens07 ON chatmensagens USING btree (id_para NULLS FIRST, lido NULLS FIRST);


--
-- TOC entry 2412 (class 1259 OID 115491)
-- Dependencies: 192 2923
-- Name: idx_chatmensagensstatus01; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_chatmensagensstatus01 ON chatmensagensstatus USING btree (id_de NULLS FIRST);


--
-- TOC entry 2413 (class 1259 OID 115492)
-- Dependencies: 192 2923
-- Name: idx_chatmensagensstatus02; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_chatmensagensstatus02 ON chatmensagensstatus USING btree (id_para NULLS FIRST);


--
-- TOC entry 2414 (class 1259 OID 115493)
-- Dependencies: 192 192 2923
-- Name: idx_chatmensagensstatus03; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_chatmensagensstatus03 ON chatmensagensstatus USING btree (id_de NULLS FIRST, id_para NULLS FIRST);


--
-- TOC entry 2415 (class 1259 OID 115494)
-- Dependencies: 192 192 2923
-- Name: idx_chatmensagensstatus04; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_chatmensagensstatus04 ON chatmensagensstatus USING btree (id_para NULLS FIRST, id_de NULLS FIRST);


--
-- TOC entry 2454 (class 1259 OID 115495)
-- Dependencies: 224 2923
-- Name: idx_conteudodigital_acessos; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_conteudodigital_acessos ON conteudodigital USING btree (acessos DESC NULLS LAST);


--
-- TOC entry 2455 (class 1259 OID 115496)
-- Dependencies: 224 224 2923
-- Name: idx_conteudodigital_avaliacao; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_conteudodigital_avaliacao ON conteudodigital USING btree (avaliacao DESC NULLS LAST, acessos DESC NULLS LAST);


--
-- TOC entry 2456 (class 1259 OID 115497)
-- Dependencies: 224 2923
-- Name: idx_conteudodigital_descricao; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_conteudodigital_descricao ON conteudodigital USING hash (descricao);


--
-- TOC entry 2457 (class 1259 OID 115498)
-- Dependencies: 224 2923
-- Name: idx_conteudodigital_titulo; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_conteudodigital_titulo ON conteudodigital USING btree (titulo);


--
-- TOC entry 2525 (class 1259 OID 115499)
-- Dependencies: 278 2923
-- Name: idx_tag_nometag; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_tag_nometag ON tag USING btree (nometag NULLS FIRST);


--
-- TOC entry 2528 (class 1259 OID 115500)
-- Dependencies: 280 2923
-- Name: idx_usuario_email; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_usuario_email ON usuario USING btree (email NULLS FIRST);


--
-- TOC entry 2529 (class 1259 OID 115501)
-- Dependencies: 280 2923
-- Name: idx_usuario_emailpessoal; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_usuario_emailpessoal ON usuario USING btree (emailpessoal NULLS FIRST);


--
-- TOC entry 2530 (class 1259 OID 115502)
-- Dependencies: 280 2923
-- Name: idx_usuario_matricula; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_usuario_matricula ON usuario USING btree (matricula NULLS FIRST);


--
-- TOC entry 2531 (class 1259 OID 115503)
-- Dependencies: 280 2923
-- Name: idx_usuario_nomeusuario; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_usuario_nomeusuario ON usuario USING btree (nomeusuario NULLS FIRST);


--
-- TOC entry 2400 (class 1259 OID 115504)
-- Dependencies: 188 2923
-- Name: nome_categoriacomponentecurricular; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX nome_categoriacomponentecurricular ON categoriacomponentecurricular USING btree (nomecategoriacomponentecurricular NULLS FIRST);


--
-- TOC entry 2395 (class 1259 OID 115505)
-- Dependencies: 184 2923
-- Name: tipo_blogcomentario; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX tipo_blogcomentario ON blogcomentario USING btree (tipo);


--
-- TOC entry 2378 (class 1259 OID 115506)
-- Dependencies: 170 2923
-- Name: tipoalbum_albumcomentario; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX tipoalbum_albumcomentario ON albumcomentario USING btree (tipoalbum NULLS FIRST);


--
-- TOC entry 2379 (class 1259 OID 115507)
-- Dependencies: 170 2923
-- Name: tipocomentario_albumcomentario; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX tipocomentario_albumcomentario ON albumcomentario USING btree (tipocomentario NULLS FIRST);


--
-- TOC entry 2659 (class 2620 OID 115508)
-- Dependencies: 324 170 2923
-- Name: tr_acud_albumcomentario; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_albumcomentario AFTER INSERT OR DELETE OR UPDATE ON albumcomentario FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2660 (class 2620 OID 115509)
-- Dependencies: 179 324 2923
-- Name: tr_acud_ambientedeapoiofavorito; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_ambientedeapoiofavorito AFTER INSERT OR DELETE OR UPDATE ON ambientedeapoiofavorito FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2661 (class 2620 OID 115510)
-- Dependencies: 324 184 2923
-- Name: tr_acud_blogcomentario; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_blogcomentario AFTER INSERT OR DELETE OR UPDATE ON blogcomentario FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2663 (class 2620 OID 115511)
-- Dependencies: 324 200 2923
-- Name: tr_acud_comunidade; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_comunidade AFTER INSERT OR DELETE OR UPDATE ON comunidade FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2664 (class 2620 OID 115512)
-- Dependencies: 201 324 2923
-- Name: tr_acud_comunidadeagenda; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_comunidadeagenda AFTER INSERT OR DELETE OR UPDATE ON comunidadeagenda FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2665 (class 2620 OID 115513)
-- Dependencies: 324 203 2923
-- Name: tr_acud_comunidadealbum; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_comunidadealbum AFTER INSERT OR DELETE OR UPDATE ON comunidadealbum FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2666 (class 2620 OID 115514)
-- Dependencies: 205 324 2923
-- Name: tr_acud_comunidadealbumfoto; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_comunidadealbumfoto AFTER INSERT OR DELETE OR UPDATE ON comunidadealbumfoto FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2667 (class 2620 OID 115515)
-- Dependencies: 207 324 2923
-- Name: tr_acud_comunidadeblog; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_comunidadeblog AFTER INSERT OR DELETE OR UPDATE ON comunidadeblog FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2668 (class 2620 OID 115516)
-- Dependencies: 324 211 2923
-- Name: tr_acud_comunidadesugerida; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_comunidadesugerida AFTER INSERT OR DELETE OR UPDATE ON comunidadesugerida FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2669 (class 2620 OID 115517)
-- Dependencies: 216 324 2923
-- Name: tr_acud_comutopico; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_comutopico AFTER INSERT OR DELETE OR UPDATE ON comutopico FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2670 (class 2620 OID 115518)
-- Dependencies: 324 218 2923
-- Name: tr_acud_comutopicomsg; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_comutopicomsg AFTER INSERT OR DELETE OR UPDATE ON comutopicomsg FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2671 (class 2620 OID 115519)
-- Dependencies: 219 324 2923
-- Name: tr_acud_comuusuario; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_comuusuario AFTER INSERT OR DELETE OR UPDATE ON comuusuario FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2672 (class 2620 OID 115520)
-- Dependencies: 324 230 2923
-- Name: tr_acud_conteudodigitalfavorito; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_conteudodigitalfavorito AFTER INSERT OR DELETE OR UPDATE ON conteudodigitalfavorito FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2673 (class 2620 OID 115521)
-- Dependencies: 244 324 2923
-- Name: tr_acud_enquete; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_enquete AFTER INSERT OR DELETE OR UPDATE ON enquete FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2675 (class 2620 OID 115522)
-- Dependencies: 265 324 2923
-- Name: tr_acud_marcacaoagenda; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_marcacaoagenda AFTER INSERT OR DELETE OR UPDATE ON marcacaoagenda FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2677 (class 2620 OID 115523)
-- Dependencies: 282 324 2923
-- Name: tr_acud_usuarioagenda; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_usuarioagenda AFTER INSERT OR DELETE OR UPDATE ON usuarioagenda FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2678 (class 2620 OID 115524)
-- Dependencies: 324 283 2923
-- Name: tr_acud_usuarioalbum; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_usuarioalbum AFTER INSERT OR DELETE OR UPDATE ON usuarioalbum FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2679 (class 2620 OID 115525)
-- Dependencies: 285 324 2923
-- Name: tr_acud_usuarioalbumfoto; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_usuarioalbumfoto AFTER INSERT OR DELETE OR UPDATE ON usuarioalbumfoto FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2680 (class 2620 OID 115527)
-- Dependencies: 289 324 2923
-- Name: tr_acud_usuariocolega; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_usuariocolega AFTER INSERT OR DELETE OR UPDATE ON usuariocolega FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2681 (class 2620 OID 115528)
-- Dependencies: 295 324 2923
-- Name: tr_acud_usuariorecado; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_acud_usuariorecado AFTER INSERT OR DELETE OR UPDATE ON usuariorecado FOR EACH ROW EXECUTE PROCEDURE inserir_feed_espacobaerto();


--
-- TOC entry 2674 (class 2620 OID 115529)
-- Dependencies: 317 255 2923
-- Name: tr_au_feedcontagem; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_au_feedcontagem AFTER UPDATE ON feedcontagem FOR EACH ROW EXECUTE PROCEDURE atualizar_chat_mensagens_status();


--
-- TOC entry 2662 (class 2620 OID 115530)
-- Dependencies: 322 190 2923
-- Name: tr_b_uc_chatmensagens; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_b_uc_chatmensagens BEFORE INSERT OR UPDATE ON chatmensagens FOR EACH ROW EXECUTE PROCEDURE inserir_mensagens_chat();


--
-- TOC entry 2676 (class 2620 OID 115531)
-- Dependencies: 316 280 2923
-- Name: tr_d_usuario; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_d_usuario BEFORE DELETE ON usuario FOR EACH ROW EXECUTE PROCEDURE apagar_usuario();


--
-- TOC entry 2560 (class 2606 OID 115532)
-- Dependencies: 165 165 2370 2923
-- Name: fk_acessibilidade_idacessibilidadepai; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY acessibilidade
    ADD CONSTRAINT fk_acessibilidade_idacessibilidadepai FOREIGN KEY (idacessibilidadepai) REFERENCES acessibilidade(idacessibilidade);


--
-- TOC entry 2561 (class 2606 OID 115537)
-- Dependencies: 2532 280 167 2923
-- Name: fk_agendacomentario_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY agendacomentario
    ADD CONSTRAINT fk_agendacomentario_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2562 (class 2606 OID 115542)
-- Dependencies: 170 280 2532 2923
-- Name: fk_albumcomentario_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY albumcomentario
    ADD CONSTRAINT fk_albumcomentario_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2563 (class 2606 OID 115547)
-- Dependencies: 174 280 2532 2923
-- Name: fk_ambientedeapoio_idusuariopublicador; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ambientedeapoio
    ADD CONSTRAINT fk_ambientedeapoio_idusuariopublicador FOREIGN KEY (idusuariopublicador) REFERENCES usuario(idusuario);


--
-- TOC entry 2571 (class 2606 OID 115552)
-- Dependencies: 2532 184 280 2923
-- Name: fk_blogcomentario_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY blogcomentario
    ADD CONSTRAINT fk_blogcomentario_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2572 (class 2606 OID 115557)
-- Dependencies: 280 190 2532 2923
-- Name: fk_chatmensagens_id_de; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY chatmensagens
    ADD CONSTRAINT fk_chatmensagens_id_de FOREIGN KEY (id_de) REFERENCES usuario(idusuario);


--
-- TOC entry 2573 (class 2606 OID 115562)
-- Dependencies: 280 2532 190 2923
-- Name: fk_chatmensagens_id_para; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY chatmensagens
    ADD CONSTRAINT fk_chatmensagens_id_para FOREIGN KEY (id_para) REFERENCES usuario(idusuario);


--
-- TOC entry 2574 (class 2606 OID 115567)
-- Dependencies: 192 280 2532 2923
-- Name: fk_chatmensagensstatus_id_de; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY chatmensagensstatus
    ADD CONSTRAINT fk_chatmensagensstatus_id_de FOREIGN KEY (id_de) REFERENCES usuario(idusuario);


--
-- TOC entry 2575 (class 2606 OID 115572)
-- Dependencies: 192 280 2532 2923
-- Name: fk_chatmensagensstatus_id_para; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY chatmensagensstatus
    ADD CONSTRAINT fk_chatmensagensstatus_id_para FOREIGN KEY (id_para) REFERENCES usuario(idusuario);


--
-- TOC entry 2576 (class 2606 OID 115577)
-- Dependencies: 195 188 2398 2923
-- Name: fk_componentecurricular_idcategoriacomponentecurricular; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY componentecurricular
    ADD CONSTRAINT fk_componentecurricular_idcategoriacomponentecurricular FOREIGN KEY (idcategoriacomponentecurricular) REFERENCES categoriacomponentecurricular(idcategoriacomponentecurricular);


--
-- TOC entry 2577 (class 2606 OID 115582)
-- Dependencies: 195 269 2517 2923
-- Name: fk_componentecurricular_idnivelensino; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY componentecurricular
    ADD CONSTRAINT fk_componentecurricular_idnivelensino FOREIGN KEY (idnivelensino) REFERENCES nivelensino(idnivelensino) ON DELETE CASCADE;


--
-- TOC entry 2578 (class 2606 OID 115587)
-- Dependencies: 196 195 2416 2923
-- Name: fk_componentecurriculartopico_idcomponentecurricular; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY componentecurriculartopico
    ADD CONSTRAINT fk_componentecurriculartopico_idcomponentecurricular FOREIGN KEY (idcomponentecurricular) REFERENCES componentecurricular(idcomponentecurricular);


--
-- TOC entry 2579 (class 2606 OID 115592)
-- Dependencies: 196 196 2418 2923
-- Name: fk_componentecurriculartopico_idcomponentecurriculartopicopai; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY componentecurriculartopico
    ADD CONSTRAINT fk_componentecurriculartopico_idcomponentecurriculartopicopai FOREIGN KEY (idcomponentecurriculartopicopai) REFERENCES componentecurriculartopico(idcomponentecurriculartopico);


--
-- TOC entry 2582 (class 2606 OID 115597)
-- Dependencies: 201 2423 200 2923
-- Name: fk_comuagenda_idcomunidade; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comunidadeagenda
    ADD CONSTRAINT fk_comuagenda_idcomunidade FOREIGN KEY (idcomunidade) REFERENCES comunidade(idcomunidade) ON DELETE CASCADE;


--
-- TOC entry 2580 (class 2606 OID 115602)
-- Dependencies: 200 2491 254 2923
-- Name: fk_comunidade_idfavorito; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comunidade
    ADD CONSTRAINT fk_comunidade_idfavorito FOREIGN KEY (idfavorito) REFERENCES favorito(idfavorito);


--
-- TOC entry 2581 (class 2606 OID 115607)
-- Dependencies: 2532 280 200 2923
-- Name: fk_comunidade_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comunidade
    ADD CONSTRAINT fk_comunidade_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2583 (class 2606 OID 115612)
-- Dependencies: 207 2423 200 2923
-- Name: fk_comunidadeblog_idcomunidade; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comunidadeblog
    ADD CONSTRAINT fk_comunidadeblog_idcomunidade FOREIGN KEY (idcomunidade) REFERENCES comunidade(idcomunidade) ON DELETE CASCADE;


--
-- TOC entry 2584 (class 2606 OID 115617)
-- Dependencies: 2532 280 207 2923
-- Name: fk_comunidadeblog_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comunidadeblog
    ADD CONSTRAINT fk_comunidadeblog_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2585 (class 2606 OID 115622)
-- Dependencies: 200 2423 211 2923
-- Name: fk_comunidadesugerida_idcomunidade; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comunidadesugerida
    ADD CONSTRAINT fk_comunidadesugerida_idcomunidade FOREIGN KEY (idcomunidade) REFERENCES comunidade(idcomunidade);


--
-- TOC entry 2586 (class 2606 OID 115627)
-- Dependencies: 2532 211 280 2923
-- Name: fk_comunidadesugerida_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comunidadesugerida
    ADD CONSTRAINT fk_comunidadesugerida_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2587 (class 2606 OID 115632)
-- Dependencies: 212 2423 200 2923
-- Name: fk_comunidadetag_idcomunidade; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comunidadetag
    ADD CONSTRAINT fk_comunidadetag_idcomunidade FOREIGN KEY (idcomunidade) REFERENCES comunidade(idcomunidade) ON DELETE CASCADE;


--
-- TOC entry 2588 (class 2606 OID 115637)
-- Dependencies: 278 212 2526 2923
-- Name: fk_comunidadetag_idtag; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comunidadetag
    ADD CONSTRAINT fk_comunidadetag_idtag FOREIGN KEY (idtag) REFERENCES tag(idtag) ON DELETE CASCADE;


--
-- TOC entry 2589 (class 2606 OID 115642)
-- Dependencies: 2423 200 214 2923
-- Name: fk_comurelacionada_idcomunidade; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comurelacionada
    ADD CONSTRAINT fk_comurelacionada_idcomunidade FOREIGN KEY (idcomunidade) REFERENCES comunidade(idcomunidade) ON DELETE CASCADE;


--
-- TOC entry 2590 (class 2606 OID 115647)
-- Dependencies: 214 200 2423 2923
-- Name: fk_comurelacionada_idcomunidaderelacionada; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comurelacionada
    ADD CONSTRAINT fk_comurelacionada_idcomunidaderelacionada FOREIGN KEY (idcomunidaderelacionada) REFERENCES comunidade(idcomunidade) ON DELETE CASCADE;


--
-- TOC entry 2591 (class 2606 OID 115652)
-- Dependencies: 2423 200 216 2923
-- Name: fk_comutopico_idcomunidade; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comutopico
    ADD CONSTRAINT fk_comutopico_idcomunidade FOREIGN KEY (idcomunidade) REFERENCES comunidade(idcomunidade) ON DELETE CASCADE;


--
-- TOC entry 2592 (class 2606 OID 115657)
-- Dependencies: 216 280 2532 2923
-- Name: fk_comutopico_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comutopico
    ADD CONSTRAINT fk_comutopico_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2593 (class 2606 OID 115662)
-- Dependencies: 218 216 2444 2923
-- Name: fk_comutopicomsg_idcomunidadetopico; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comutopicomsg
    ADD CONSTRAINT fk_comutopicomsg_idcomunidadetopico FOREIGN KEY (idcomutopico) REFERENCES comutopico(idcomutopico) ON DELETE CASCADE;


--
-- TOC entry 2594 (class 2606 OID 115667)
-- Dependencies: 218 280 2532 2923
-- Name: fk_comutopicomsg_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comutopicomsg
    ADD CONSTRAINT fk_comutopicomsg_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2595 (class 2606 OID 115672)
-- Dependencies: 2423 219 200 2923
-- Name: fk_comuusuario_idcomunidade; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comuusuario
    ADD CONSTRAINT fk_comuusuario_idcomunidade FOREIGN KEY (idcomunidade) REFERENCES comunidade(idcomunidade) ON DELETE CASCADE;


--
-- TOC entry 2596 (class 2606 OID 115677)
-- Dependencies: 219 280 2532 2923
-- Name: fk_comuusuario_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comuusuario
    ADD CONSTRAINT fk_comuusuario_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2597 (class 2606 OID 115682)
-- Dependencies: 2423 200 222 2923
-- Name: fk_comuvoto_idcomunidade; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comuvoto
    ADD CONSTRAINT fk_comuvoto_idcomunidade FOREIGN KEY (idcomunidade) REFERENCES comunidade(idcomunidade) ON DELETE CASCADE;


--
-- TOC entry 2598 (class 2606 OID 115687)
-- Dependencies: 280 2532 222 2923
-- Name: fk_comuvoto_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comuvoto
    ADD CONSTRAINT fk_comuvoto_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2599 (class 2606 OID 115692)
-- Dependencies: 2458 225 224 2923
-- Name: fk_conteudodigital_idconteudodigitalcategoria; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigital
    ADD CONSTRAINT fk_conteudodigital_idconteudodigitalcategoria FOREIGN KEY (idconteudodigitalcategoria) REFERENCES conteudodigitalcategoria(idconteudodigitalcategoria);


--
-- TOC entry 2600 (class 2606 OID 115697)
-- Dependencies: 264 224 2511 2923
-- Name: fk_conteudodigital_idformato; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigital
    ADD CONSTRAINT fk_conteudodigital_idformato FOREIGN KEY (idformato) REFERENCES formato(idformato);


--
-- TOC entry 2601 (class 2606 OID 115702)
-- Dependencies: 2473 224 235 2923
-- Name: fk_conteudodigital_idlicencaconteudo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigital
    ADD CONSTRAINT fk_conteudodigital_idlicencaconteudo FOREIGN KEY (idlicencaconteudo) REFERENCES conteudolicenca(idconteudolicenca);


--
-- TOC entry 2602 (class 2606 OID 115707)
-- Dependencies: 275 224 2523 2923
-- Name: fk_conteudodigital_idservidor; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigital
    ADD CONSTRAINT fk_conteudodigital_idservidor FOREIGN KEY (idservidor) REFERENCES servidor(idservidor);


--
-- TOC entry 2603 (class 2606 OID 115712)
-- Dependencies: 280 224 2532 2923
-- Name: fk_conteudodigital_idusuarioaprova; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigital
    ADD CONSTRAINT fk_conteudodigital_idusuarioaprova FOREIGN KEY (idusuarioaprova) REFERENCES usuario(idusuario);


--
-- TOC entry 2604 (class 2606 OID 115717)
-- Dependencies: 280 224 2532 2923
-- Name: fk_conteudodigital_idusuariopublicador; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigital
    ADD CONSTRAINT fk_conteudodigital_idusuariopublicador FOREIGN KEY (idusuariopublicador) REFERENCES usuario(idusuario);


--
-- TOC entry 2605 (class 2606 OID 115722)
-- Dependencies: 225 225 2458 2923
-- Name: fk_conteudodigitalcategoria_conteudodigitalcategoriapai; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigitalcategoria
    ADD CONSTRAINT fk_conteudodigitalcategoria_conteudodigitalcategoriapai FOREIGN KEY (idconteudodigitalcategoriapai) REFERENCES conteudodigitalcategoria(idconteudodigitalcategoria);


--
-- TOC entry 2606 (class 2606 OID 115727)
-- Dependencies: 2396 225 186 2923
-- Name: fk_conteudodigitalcategoria_idcanal; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigitalcategoria
    ADD CONSTRAINT fk_conteudodigitalcategoria_idcanal FOREIGN KEY (idcanal) REFERENCES canal(idcanal);


--
-- TOC entry 2607 (class 2606 OID 115732)
-- Dependencies: 228 224 2452 2923
-- Name: fk_conteudodigitalcomentario_idconteudodigital; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigitalcomentario
    ADD CONSTRAINT fk_conteudodigitalcomentario_idconteudodigital FOREIGN KEY (idconteudodigital) REFERENCES conteudodigital(idconteudodigital) ON DELETE CASCADE;


--
-- TOC entry 2608 (class 2606 OID 115737)
-- Dependencies: 2532 228 280 2923
-- Name: fk_conteudodigitalcomentario_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigitalcomentario
    ADD CONSTRAINT fk_conteudodigitalcomentario_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2609 (class 2606 OID 115742)
-- Dependencies: 195 229 2416 2923
-- Name: fk_conteudodigitalcomponente_idcomponentecurricular; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigitalcomponente
    ADD CONSTRAINT fk_conteudodigitalcomponente_idcomponentecurricular FOREIGN KEY (idcomponentecurricular) REFERENCES componentecurricular(idcomponentecurricular) ON DELETE CASCADE;


--
-- TOC entry 2610 (class 2606 OID 115747)
-- Dependencies: 224 229 2452 2923
-- Name: fk_conteudodigitalcomponente_idconteudodigital; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigitalcomponente
    ADD CONSTRAINT fk_conteudodigitalcomponente_idconteudodigital FOREIGN KEY (idconteudodigital) REFERENCES conteudodigital(idconteudodigital) ON DELETE CASCADE;


--
-- TOC entry 2611 (class 2606 OID 115752)
-- Dependencies: 224 2452 230 2923
-- Name: fk_conteudodigitalfavorito_idconteudodigital; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigitalfavorito
    ADD CONSTRAINT fk_conteudodigitalfavorito_idconteudodigital FOREIGN KEY (idconteudodigital) REFERENCES conteudodigital(idconteudodigital) ON DELETE CASCADE;


--
-- TOC entry 2612 (class 2606 OID 115757)
-- Dependencies: 2491 230 254 2923
-- Name: fk_conteudodigitalfavorito_idfavorito; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigitalfavorito
    ADD CONSTRAINT fk_conteudodigitalfavorito_idfavorito FOREIGN KEY (idfavorito) REFERENCES favorito(idfavorito);


--
-- TOC entry 2613 (class 2606 OID 115762)
-- Dependencies: 231 224 2452 2923
-- Name: fk_conteudodigitalrelacionado_idconteudodigital; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigitalrelacionado
    ADD CONSTRAINT fk_conteudodigitalrelacionado_idconteudodigital FOREIGN KEY (idconteudodigital) REFERENCES conteudodigital(idconteudodigital) ON DELETE CASCADE;


--
-- TOC entry 2614 (class 2606 OID 115767)
-- Dependencies: 232 2452 224 2923
-- Name: fk_conteudodigitaltag_idconteudodigital; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigitaltag
    ADD CONSTRAINT fk_conteudodigitaltag_idconteudodigital FOREIGN KEY (idconteudodigital) REFERENCES conteudodigital(idconteudodigital) ON DELETE CASCADE;


--
-- TOC entry 2615 (class 2606 OID 115772)
-- Dependencies: 232 2526 278 2923
-- Name: fk_conteudodigitaltag_idtag; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigitaltag
    ADD CONSTRAINT fk_conteudodigitaltag_idtag FOREIGN KEY (idtag) REFERENCES tag(idtag) ON DELETE CASCADE;


--
-- TOC entry 2616 (class 2606 OID 115777)
-- Dependencies: 2452 224 234 2923
-- Name: fk_conteudodigitalvoto_idconteudodigital; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigitalvoto
    ADD CONSTRAINT fk_conteudodigitalvoto_idconteudodigital FOREIGN KEY (idconteudodigital) REFERENCES conteudodigital(idconteudodigital) ON DELETE CASCADE;


--
-- TOC entry 2617 (class 2606 OID 115782)
-- Dependencies: 234 2532 280 2923
-- Name: fk_conteudodigitalvoto_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudodigitalvoto
    ADD CONSTRAINT fk_conteudodigitalvoto_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2618 (class 2606 OID 115787)
-- Dependencies: 235 235 2473 2923
-- Name: fk_conteudolicenca_idconteudolicencapai; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY conteudolicenca
    ADD CONSTRAINT fk_conteudolicenca_idconteudolicencapai FOREIGN KEY (idconteudolicencapai) REFERENCES conteudolicenca(idconteudolicenca);


--
-- TOC entry 2619 (class 2606 OID 115792)
-- Dependencies: 240 2532 280 2923
-- Name: fk_denuncia_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY denuncia
    ADD CONSTRAINT fk_denuncia_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2620 (class 2606 OID 115797)
-- Dependencies: 200 2423 244 2923
-- Name: fk_enquete_idcomunidade; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY enquete
    ADD CONSTRAINT fk_enquete_idcomunidade FOREIGN KEY (idcomunidade) REFERENCES comunidade(idcomunidade) ON DELETE CASCADE;


--
-- TOC entry 2621 (class 2606 OID 115802)
-- Dependencies: 244 2532 280 2923
-- Name: fk_enquete_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY enquete
    ADD CONSTRAINT fk_enquete_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2622 (class 2606 OID 115807)
-- Dependencies: 2481 246 244 2923
-- Name: fk_enqueteopcao_idenquete; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY enqueteopcao
    ADD CONSTRAINT fk_enqueteopcao_idenquete FOREIGN KEY (idenquete) REFERENCES enquete(idenquete) ON DELETE CASCADE;


--
-- TOC entry 2623 (class 2606 OID 115812)
-- Dependencies: 248 2483 246 2923
-- Name: fk_enqueteopcaoresposta_idenqueteopcao; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY enqueteopcaoresposta
    ADD CONSTRAINT fk_enqueteopcaoresposta_idenqueteopcao FOREIGN KEY (idenqueteopcao) REFERENCES enqueteopcao(idenqueteopcao) ON DELETE CASCADE;


--
-- TOC entry 2624 (class 2606 OID 115817)
-- Dependencies: 248 280 2532 2923
-- Name: fk_enqueteopcaoresposta_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY enqueteopcaoresposta
    ADD CONSTRAINT fk_enqueteopcaoresposta_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2625 (class 2606 OID 115822)
-- Dependencies: 267 2515 250 2923
-- Name: fk_escola_idmunicipio; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY escola
    ADD CONSTRAINT fk_escola_idmunicipio FOREIGN KEY (idmunicipio) REFERENCES municipio(idmunicipio);


--
-- TOC entry 2626 (class 2606 OID 115827)
-- Dependencies: 2479 255 241 2923
-- Name: fk_feedcontagem_iddispositivo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY feedcontagem
    ADD CONSTRAINT fk_feedcontagem_iddispositivo FOREIGN KEY (iddispositivo) REFERENCES dispositivo(iddispositivo);


--
-- TOC entry 2627 (class 2606 OID 115832)
-- Dependencies: 280 2532 255 2923
-- Name: fk_feedcontagem_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY feedcontagem
    ADD CONSTRAINT fk_feedcontagem_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2628 (class 2606 OID 115837)
-- Dependencies: 257 2423 200 2923
-- Name: fk_feeddetalhe_idcomunidade; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY feeddetalhe
    ADD CONSTRAINT fk_feeddetalhe_idcomunidade FOREIGN KEY (idcomunidade) REFERENCES comunidade(idcomunidade);


--
-- TOC entry 2629 (class 2606 OID 115842)
-- Dependencies: 257 2508 261 2923
-- Name: fk_feeddetalhe_idfeedmensagem; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY feeddetalhe
    ADD CONSTRAINT fk_feeddetalhe_idfeedmensagem FOREIGN KEY (idfeedmensagem) REFERENCES feedtipo(id);


--
-- TOC entry 2630 (class 2606 OID 115847)
-- Dependencies: 257 259 2506 2923
-- Name: fk_feeddetalhe_idfeedtabela; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY feeddetalhe
    ADD CONSTRAINT fk_feeddetalhe_idfeedtabela FOREIGN KEY (idfeedtabela) REFERENCES feedtabela(id);


--
-- TOC entry 2631 (class 2606 OID 115852)
-- Dependencies: 257 280 2532 2923
-- Name: fk_feeddetalhe_idusuariodestinatario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY feeddetalhe
    ADD CONSTRAINT fk_feeddetalhe_idusuariodestinatario FOREIGN KEY (idusuariodestinatario) REFERENCES usuario(idusuario);


--
-- TOC entry 2632 (class 2606 OID 115857)
-- Dependencies: 257 2532 280 2923
-- Name: fk_feeddetalhe_idusuarioremetente; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY feeddetalhe
    ADD CONSTRAINT fk_feeddetalhe_idusuarioremetente FOREIGN KEY (idusuarioremetente) REFERENCES usuario(idusuario);


--
-- TOC entry 2633 (class 2606 OID 115862)
-- Dependencies: 264 238 2475 2923
-- Name: fk_formato_idconteudotipo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY formato
    ADD CONSTRAINT fk_formato_idconteudotipo FOREIGN KEY (idconteudotipo) REFERENCES conteudotipo(idconteudotipo) ON DELETE CASCADE;


--
-- TOC entry 2634 (class 2606 OID 115867)
-- Dependencies: 265 2532 280 2923
-- Name: fk_marcacaoagenda_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY marcacaoagenda
    ADD CONSTRAINT fk_marcacaoagenda_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2635 (class 2606 OID 115872)
-- Dependencies: 252 2489 267 2923
-- Name: fk_municipio_idestado; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY municipio
    ADD CONSTRAINT fk_municipio_idestado FOREIGN KEY (idestado) REFERENCES estado(idestado);


--
-- TOC entry 2564 (class 2606 OID 115877)
-- Dependencies: 174 176 2382 2923
-- Name: fk_sistema_idtiposistema; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ambientedeapoio
    ADD CONSTRAINT fk_sistema_idtiposistema FOREIGN KEY (idambientedeapoiocategoria) REFERENCES ambientedeapoiocategoria(idambientedeapoiocategoria) ON DELETE CASCADE;


--
-- TOC entry 2565 (class 2606 OID 115882)
-- Dependencies: 174 2380 178 2923
-- Name: fk_sistemacomentario_sistema; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ambientedeapoiocomentario
    ADD CONSTRAINT fk_sistemacomentario_sistema FOREIGN KEY (idambientedeapoio) REFERENCES ambientedeapoio(idambientedeapoio) ON DELETE CASCADE;


--
-- TOC entry 2566 (class 2606 OID 115887)
-- Dependencies: 280 2532 178 2923
-- Name: fk_sistemacomentario_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ambientedeapoiocomentario
    ADD CONSTRAINT fk_sistemacomentario_usuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2567 (class 2606 OID 115892)
-- Dependencies: 254 179 2491 2923
-- Name: fk_sistemafavorito_idfavorito; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ambientedeapoiofavorito
    ADD CONSTRAINT fk_sistemafavorito_idfavorito FOREIGN KEY (idfavorito) REFERENCES favorito(idfavorito);


--
-- TOC entry 2568 (class 2606 OID 115897)
-- Dependencies: 174 2380 179 2923
-- Name: fk_sistemafavorito_idsistema; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ambientedeapoiofavorito
    ADD CONSTRAINT fk_sistemafavorito_idsistema FOREIGN KEY (idambientedeapoio) REFERENCES ambientedeapoio(idambientedeapoio) ON DELETE CASCADE;


--
-- TOC entry 2569 (class 2606 OID 115902)
-- Dependencies: 180 2380 174 2923
-- Name: fk_sistematag_idsistema; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ambientedeapoiotag
    ADD CONSTRAINT fk_sistematag_idsistema FOREIGN KEY (idambientedeapoio) REFERENCES ambientedeapoio(idambientedeapoio) ON DELETE CASCADE;


--
-- TOC entry 2570 (class 2606 OID 115907)
-- Dependencies: 278 180 2526 2923
-- Name: fk_sistematag_idtag; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ambientedeapoiotag
    ADD CONSTRAINT fk_sistematag_idtag FOREIGN KEY (idtag) REFERENCES tag(idtag) ON DELETE CASCADE;


--
-- TOC entry 2636 (class 2606 OID 115912)
-- Dependencies: 2487 250 280 2923
-- Name: fk_usuario_idescola; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT fk_usuario_idescola FOREIGN KEY (idescola) REFERENCES escola(idescola);


--
-- TOC entry 2637 (class 2606 OID 115917)
-- Dependencies: 280 254 2491 2923
-- Name: fk_usuario_idfavorito; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT fk_usuario_idfavorito FOREIGN KEY (idfavorito) REFERENCES favorito(idfavorito);


--
-- TOC entry 2638 (class 2606 OID 115922)
-- Dependencies: 280 267 2515 2923
-- Name: fk_usuario_idmunicipio; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT fk_usuario_idmunicipio FOREIGN KEY (idmunicipio) REFERENCES municipio(idmunicipio);


--
-- TOC entry 2639 (class 2606 OID 115927)
-- Dependencies: 274 280 2521 2923
-- Name: fk_usuario_idserie; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT fk_usuario_idserie FOREIGN KEY (idserie) REFERENCES serie(idserie);


--
-- TOC entry 2640 (class 2606 OID 115932)
-- Dependencies: 280 301 2558 2923
-- Name: fk_usuario_idusuariotipo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT fk_usuario_idusuariotipo FOREIGN KEY (idusuariotipo) REFERENCES usuariotipo(idusuariotipo);


--
-- TOC entry 2641 (class 2606 OID 115937)
-- Dependencies: 282 280 2532 2923
-- Name: fk_usuarioagenda_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarioagenda
    ADD CONSTRAINT fk_usuarioagenda_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2642 (class 2606 OID 115942)
-- Dependencies: 283 280 2532 2923
-- Name: fk_usuarioalbum_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarioalbum
    ADD CONSTRAINT fk_usuarioalbum_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario) ON DELETE CASCADE;


--
-- TOC entry 2643 (class 2606 OID 115947)
-- Dependencies: 283 285 2536 2923
-- Name: fk_usuarioalbumfoto_idusuarioalbum; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarioalbumfoto
    ADD CONSTRAINT fk_usuarioalbumfoto_idusuarioalbum FOREIGN KEY (idusuarioalbum) REFERENCES usuarioalbum(idusuarioalbum) ON DELETE CASCADE;


--
-- TOC entry 2644 (class 2606 OID 115952)
-- Dependencies: 280 288 2532 2923
-- Name: fk_usuarioamigo_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarioamigo
    ADD CONSTRAINT fk_usuarioamigo_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2645 (class 2606 OID 115957)
-- Dependencies: 288 280 2532 2923
-- Name: fk_usuarioamigo_idusuarioaprovar; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarioamigo
    ADD CONSTRAINT fk_usuarioamigo_idusuarioaprovar FOREIGN KEY (idusuarioaprovar) REFERENCES usuario(idusuario);


--
-- TOC entry 2646 (class 2606 OID 115962)
-- Dependencies: 2532 280 288 2923
-- Name: fk_usuarioamigo_idusuarioindicou; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarioamigo
    ADD CONSTRAINT fk_usuarioamigo_idusuarioindicou FOREIGN KEY (idusuarioindicou) REFERENCES usuario(idusuario);


--
-- TOC entry 2647 (class 2606 OID 115972)
-- Dependencies: 280 289 2532 2923
-- Name: fk_usuariocolega_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuariocolega
    ADD CONSTRAINT fk_usuariocolega_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2648 (class 2606 OID 115977)
-- Dependencies: 195 2416 291 2923
-- Name: fk_usuariocomponente_idcomponentecurricular; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuariocomponente
    ADD CONSTRAINT fk_usuariocomponente_idcomponentecurricular FOREIGN KEY (idcomponentecurricular) REFERENCES componentecurricular(idcomponentecurricular) ON DELETE CASCADE;


--
-- TOC entry 2649 (class 2606 OID 115982)
-- Dependencies: 291 2532 280 2923
-- Name: fk_usuariocomponente_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuariocomponente
    ADD CONSTRAINT fk_usuariocomponente_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario) ON DELETE CASCADE;


--
-- TOC entry 2650 (class 2606 OID 115987)
-- Dependencies: 280 2532 292 2923
-- Name: fk_usuariofoto_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuariofoto
    ADD CONSTRAINT fk_usuariofoto_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2651 (class 2606 OID 115992)
-- Dependencies: 295 2532 280 2923
-- Name: fk_usuariorecado_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuariorecado
    ADD CONSTRAINT fk_usuariorecado_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2652 (class 2606 OID 115997)
-- Dependencies: 295 280 2532 2923
-- Name: fk_usuariorecado_idusuarioautor; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuariorecado
    ADD CONSTRAINT fk_usuariorecado_idusuarioautor FOREIGN KEY (idusuarioautor) REFERENCES usuario(idusuario);


--
-- TOC entry 2653 (class 2606 OID 116002)
-- Dependencies: 2550 295 295 2923
-- Name: fk_usuariorecado_idusuariorecado; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuariorecado
    ADD CONSTRAINT fk_usuariorecado_idusuariorecado FOREIGN KEY (idrecadorelacionado) REFERENCES usuariorecado(idusuariorecado);


--
-- TOC entry 2654 (class 2606 OID 116007)
-- Dependencies: 296 2519 271 2923
-- Name: fk_usuarioredesocial_idredesocial; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarioredesocial
    ADD CONSTRAINT fk_usuarioredesocial_idredesocial FOREIGN KEY (idredesocial) REFERENCES redesocial(idredesocial) ON DELETE CASCADE;


--
-- TOC entry 2655 (class 2606 OID 116012)
-- Dependencies: 2532 280 296 2923
-- Name: fk_usuarioredesocial_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarioredesocial
    ADD CONSTRAINT fk_usuarioredesocial_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2656 (class 2606 OID 116017)
-- Dependencies: 298 280 2532 2923
-- Name: fk_usuariosobremimperfil_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuariosobremimperfil
    ADD CONSTRAINT fk_usuariosobremimperfil_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2657 (class 2606 OID 116022)
-- Dependencies: 2526 299 278 2923
-- Name: fk_usuariotag_idtag; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuariotag
    ADD CONSTRAINT fk_usuariotag_idtag FOREIGN KEY (idtag) REFERENCES tag(idtag) ON DELETE CASCADE;


--
-- TOC entry 2658 (class 2606 OID 116027)
-- Dependencies: 299 280 2532 2923
-- Name: fk_usuariotag_idusuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuariotag
    ADD CONSTRAINT fk_usuariotag_idusuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario);


--
-- TOC entry 2928 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- TOC entry 2930 (class 0 OID 0)
-- Dependencies: 317
-- Name: atualizar_chat_mensagens_status(); Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON FUNCTION atualizar_chat_mensagens_status() FROM PUBLIC;
REVOKE ALL ON FUNCTION atualizar_chat_mensagens_status() FROM postgres;
GRANT ALL ON FUNCTION atualizar_chat_mensagens_status() TO postgres;
GRANT ALL ON FUNCTION atualizar_chat_mensagens_status() TO PUBLIC;


--
-- TOC entry 2931 (class 0 OID 0)
-- Dependencies: 318
-- Name: atualizar_mensagem_chat_lidos(integer, integer); Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON FUNCTION atualizar_mensagem_chat_lidos(idusuario_remetente integer, idusuario_destinatario integer) FROM PUBLIC;
REVOKE ALL ON FUNCTION atualizar_mensagem_chat_lidos(idusuario_remetente integer, idusuario_destinatario integer) FROM postgres;
GRANT ALL ON FUNCTION atualizar_mensagem_chat_lidos(idusuario_remetente integer, idusuario_destinatario integer) TO postgres;
GRANT ALL ON FUNCTION atualizar_mensagem_chat_lidos(idusuario_remetente integer, idusuario_destinatario integer) TO PUBLIC;


--
-- TOC entry 2932 (class 0 OID 0)
-- Dependencies: 319
-- Name: atualizar_mensagem_chat_status(integer, integer, boolean); Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON FUNCTION atualizar_mensagem_chat_status(idusuario_remetente integer, idusuario_destinatario integer, stavisar boolean) FROM PUBLIC;
REVOKE ALL ON FUNCTION atualizar_mensagem_chat_status(idusuario_remetente integer, idusuario_destinatario integer, stavisar boolean) FROM postgres;
GRANT ALL ON FUNCTION atualizar_mensagem_chat_status(idusuario_remetente integer, idusuario_destinatario integer, stavisar boolean) TO postgres;
GRANT ALL ON FUNCTION atualizar_mensagem_chat_status(idusuario_remetente integer, idusuario_destinatario integer, stavisar boolean) TO PUBLIC;


--
-- TOC entry 2933 (class 0 OID 0)
-- Dependencies: 327
-- Name: consulta_busca_conteudo(character varying); Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON FUNCTION consulta_busca_conteudo(texto_descricao character varying) FROM PUBLIC;
REVOKE ALL ON FUNCTION consulta_busca_conteudo(texto_descricao character varying) FROM postgres;
GRANT ALL ON FUNCTION consulta_busca_conteudo(texto_descricao character varying) TO postgres;
GRANT ALL ON FUNCTION consulta_busca_conteudo(texto_descricao character varying) TO PUBLIC;


--
-- TOC entry 2934 (class 0 OID 0)
-- Dependencies: 323
-- Name: consultar_feed_espacoaberto(integer, integer, integer); Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON FUNCTION consultar_feed_espacoaberto(idusuario integer, idfeed_min integer, idfeed_max integer) FROM PUBLIC;
REVOKE ALL ON FUNCTION consultar_feed_espacoaberto(idusuario integer, idfeed_min integer, idfeed_max integer) FROM postgres;
GRANT ALL ON FUNCTION consultar_feed_espacoaberto(idusuario integer, idfeed_min integer, idfeed_max integer) TO postgres;
GRANT ALL ON FUNCTION consultar_feed_espacoaberto(idusuario integer, idfeed_min integer, idfeed_max integer) TO PUBLIC;


--
-- TOC entry 2935 (class 0 OID 0)
-- Dependencies: 321
-- Name: consultar_mensagem_chat(integer, integer, integer); Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON FUNCTION consultar_mensagem_chat(idusuario_remetente integer, idusuario_destinatario integer, id integer) FROM PUBLIC;
REVOKE ALL ON FUNCTION consultar_mensagem_chat(idusuario_remetente integer, idusuario_destinatario integer, id integer) FROM postgres;
GRANT ALL ON FUNCTION consultar_mensagem_chat(idusuario_remetente integer, idusuario_destinatario integer, id integer) TO postgres;
GRANT ALL ON FUNCTION consultar_mensagem_chat(idusuario_remetente integer, idusuario_destinatario integer, id integer) TO PUBLIC;


--
-- TOC entry 2936 (class 0 OID 0)
-- Dependencies: 325
-- Name: consultar_mensagem_chat_status(integer, integer); Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON FUNCTION consultar_mensagem_chat_status(idusuario_remetente integer, idusuario_destinatario integer) FROM PUBLIC;
REVOKE ALL ON FUNCTION consultar_mensagem_chat_status(idusuario_remetente integer, idusuario_destinatario integer) FROM postgres;
GRANT ALL ON FUNCTION consultar_mensagem_chat_status(idusuario_remetente integer, idusuario_destinatario integer) TO postgres;
GRANT ALL ON FUNCTION consultar_mensagem_chat_status(idusuario_remetente integer, idusuario_destinatario integer) TO PUBLIC;


--
-- TOC entry 2937 (class 0 OID 0)
-- Dependencies: 324
-- Name: inserir_feed_espacobaerto(); Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON FUNCTION inserir_feed_espacobaerto() FROM PUBLIC;
REVOKE ALL ON FUNCTION inserir_feed_espacobaerto() FROM postgres;
GRANT ALL ON FUNCTION inserir_feed_espacobaerto() TO postgres;
GRANT ALL ON FUNCTION inserir_feed_espacobaerto() TO PUBLIC;


--
-- TOC entry 2938 (class 0 OID 0)
-- Dependencies: 322
-- Name: inserir_mensagens_chat(); Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON FUNCTION inserir_mensagens_chat() FROM PUBLIC;
REVOKE ALL ON FUNCTION inserir_mensagens_chat() FROM postgres;
GRANT ALL ON FUNCTION inserir_mensagens_chat() TO postgres;
GRANT ALL ON FUNCTION inserir_mensagens_chat() TO PUBLIC;


--
-- TOC entry 2939 (class 0 OID 0)
-- Dependencies: 326
-- Name: sem_acentos(character varying); Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON FUNCTION sem_acentos(character varying) FROM PUBLIC;
REVOKE ALL ON FUNCTION sem_acentos(character varying) FROM postgres;
GRANT ALL ON FUNCTION sem_acentos(character varying) TO postgres;
GRANT ALL ON FUNCTION sem_acentos(character varying) TO PUBLIC;


--
-- TOC entry 2940 (class 0 OID 0)
-- Dependencies: 165
-- Name: acessibilidade; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE acessibilidade FROM PUBLIC;
REVOKE ALL ON TABLE acessibilidade FROM postgres;
GRANT ALL ON TABLE acessibilidade TO postgres;


--
-- TOC entry 2942 (class 0 OID 0)
-- Dependencies: 166
-- Name: acessibilidade_idacessibilidade_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE acessibilidade_idacessibilidade_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE acessibilidade_idacessibilidade_seq FROM postgres;
GRANT ALL ON SEQUENCE acessibilidade_idacessibilidade_seq TO postgres;


--
-- TOC entry 2943 (class 0 OID 0)
-- Dependencies: 167
-- Name: agendacomentario; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE agendacomentario FROM PUBLIC;
REVOKE ALL ON TABLE agendacomentario FROM postgres;
GRANT ALL ON TABLE agendacomentario TO postgres;


--
-- TOC entry 2945 (class 0 OID 0)
-- Dependencies: 168
-- Name: agendacomentario_idagendacomentario_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE agendacomentario_idagendacomentario_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE agendacomentario_idagendacomentario_seq FROM postgres;
GRANT ALL ON SEQUENCE agendacomentario_idagendacomentario_seq TO postgres;


--
-- TOC entry 2946 (class 0 OID 0)
-- Dependencies: 169
-- Name: album_idalbum_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE album_idalbum_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE album_idalbum_seq FROM postgres;
GRANT ALL ON SEQUENCE album_idalbum_seq TO postgres;


--
-- TOC entry 2947 (class 0 OID 0)
-- Dependencies: 170
-- Name: albumcomentario; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE albumcomentario FROM PUBLIC;
REVOKE ALL ON TABLE albumcomentario FROM postgres;
GRANT ALL ON TABLE albumcomentario TO postgres;


--
-- TOC entry 2949 (class 0 OID 0)
-- Dependencies: 171
-- Name: albumcomentario_idalbumcomentario_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE albumcomentario_idalbumcomentario_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE albumcomentario_idalbumcomentario_seq FROM postgres;
GRANT ALL ON SEQUENCE albumcomentario_idalbumcomentario_seq TO postgres;


--
-- TOC entry 2950 (class 0 OID 0)
-- Dependencies: 172
-- Name: albumfoto_idalbumfoto_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE albumfoto_idalbumfoto_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE albumfoto_idalbumfoto_seq FROM postgres;
GRANT ALL ON SEQUENCE albumfoto_idalbumfoto_seq TO postgres;


--
-- TOC entry 2951 (class 0 OID 0)
-- Dependencies: 173
-- Name: ambientedeapoio_idambientedeapoio_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE ambientedeapoio_idambientedeapoio_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE ambientedeapoio_idambientedeapoio_seq FROM postgres;
GRANT ALL ON SEQUENCE ambientedeapoio_idambientedeapoio_seq TO postgres;


--
-- TOC entry 2952 (class 0 OID 0)
-- Dependencies: 174
-- Name: ambientedeapoio; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE ambientedeapoio FROM PUBLIC;
REVOKE ALL ON TABLE ambientedeapoio FROM postgres;
GRANT ALL ON TABLE ambientedeapoio TO postgres;


--
-- TOC entry 2953 (class 0 OID 0)
-- Dependencies: 175
-- Name: ambientedeapoiocategoria_idambientedeapoiocategoria_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE ambientedeapoiocategoria_idambientedeapoiocategoria_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE ambientedeapoiocategoria_idambientedeapoiocategoria_seq FROM postgres;
GRANT ALL ON SEQUENCE ambientedeapoiocategoria_idambientedeapoiocategoria_seq TO postgres;


--
-- TOC entry 2954 (class 0 OID 0)
-- Dependencies: 176
-- Name: ambientedeapoiocategoria; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE ambientedeapoiocategoria FROM PUBLIC;
REVOKE ALL ON TABLE ambientedeapoiocategoria FROM postgres;
GRANT ALL ON TABLE ambientedeapoiocategoria TO postgres;


--
-- TOC entry 2955 (class 0 OID 0)
-- Dependencies: 177
-- Name: ambientedeapoiocomentario_idambientedeapoiocomentario_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE ambientedeapoiocomentario_idambientedeapoiocomentario_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE ambientedeapoiocomentario_idambientedeapoiocomentario_seq FROM postgres;
GRANT ALL ON SEQUENCE ambientedeapoiocomentario_idambientedeapoiocomentario_seq TO postgres;


--
-- TOC entry 2956 (class 0 OID 0)
-- Dependencies: 178
-- Name: ambientedeapoiocomentario; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE ambientedeapoiocomentario FROM PUBLIC;
REVOKE ALL ON TABLE ambientedeapoiocomentario FROM postgres;
GRANT ALL ON TABLE ambientedeapoiocomentario TO postgres;


--
-- TOC entry 2957 (class 0 OID 0)
-- Dependencies: 179
-- Name: ambientedeapoiofavorito; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE ambientedeapoiofavorito FROM PUBLIC;
REVOKE ALL ON TABLE ambientedeapoiofavorito FROM postgres;
GRANT ALL ON TABLE ambientedeapoiofavorito TO postgres;


--
-- TOC entry 2958 (class 0 OID 0)
-- Dependencies: 180
-- Name: ambientedeapoiotag; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE ambientedeapoiotag FROM PUBLIC;
REVOKE ALL ON TABLE ambientedeapoiotag FROM postgres;
GRANT ALL ON TABLE ambientedeapoiotag TO postgres;


--
-- TOC entry 2959 (class 0 OID 0)
-- Dependencies: 181
-- Name: ava_idava_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE ava_idava_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE ava_idava_seq FROM postgres;
GRANT ALL ON SEQUENCE ava_idava_seq TO postgres;


--
-- TOC entry 2960 (class 0 OID 0)
-- Dependencies: 182
-- Name: avacomentario_idavacomentario_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE avacomentario_idavacomentario_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE avacomentario_idavacomentario_seq FROM postgres;
GRANT ALL ON SEQUENCE avacomentario_idavacomentario_seq TO postgres;


--
-- TOC entry 2961 (class 0 OID 0)
-- Dependencies: 183
-- Name: blog_idblog_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE blog_idblog_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE blog_idblog_seq FROM postgres;
GRANT ALL ON SEQUENCE blog_idblog_seq TO postgres;


--
-- TOC entry 2962 (class 0 OID 0)
-- Dependencies: 184
-- Name: blogcomentario; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE blogcomentario FROM PUBLIC;
REVOKE ALL ON TABLE blogcomentario FROM postgres;
GRANT ALL ON TABLE blogcomentario TO postgres;


--
-- TOC entry 2964 (class 0 OID 0)
-- Dependencies: 185
-- Name: blogcomentario_idblogcomentario_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE blogcomentario_idblogcomentario_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE blogcomentario_idblogcomentario_seq FROM postgres;
GRANT ALL ON SEQUENCE blogcomentario_idblogcomentario_seq TO postgres;


--
-- TOC entry 2965 (class 0 OID 0)
-- Dependencies: 186
-- Name: canal; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE canal FROM PUBLIC;
REVOKE ALL ON TABLE canal FROM postgres;
GRANT ALL ON TABLE canal TO postgres;


--
-- TOC entry 2967 (class 0 OID 0)
-- Dependencies: 187
-- Name: canal_idcanal_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE canal_idcanal_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE canal_idcanal_seq FROM postgres;
GRANT ALL ON SEQUENCE canal_idcanal_seq TO postgres;


--
-- TOC entry 2968 (class 0 OID 0)
-- Dependencies: 188
-- Name: categoriacomponentecurricular; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE categoriacomponentecurricular FROM PUBLIC;
REVOKE ALL ON TABLE categoriacomponentecurricular FROM postgres;
GRANT ALL ON TABLE categoriacomponentecurricular TO postgres;


--
-- TOC entry 2970 (class 0 OID 0)
-- Dependencies: 189
-- Name: categoriacomponentecurricular_idcategoriacomponentecurricul_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE categoriacomponentecurricular_idcategoriacomponentecurricul_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE categoriacomponentecurricular_idcategoriacomponentecurricul_seq FROM postgres;
GRANT ALL ON SEQUENCE categoriacomponentecurricular_idcategoriacomponentecurricul_seq TO postgres;


--
-- TOC entry 2971 (class 0 OID 0)
-- Dependencies: 190
-- Name: chatmensagens; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE chatmensagens FROM PUBLIC;
REVOKE ALL ON TABLE chatmensagens FROM postgres;
GRANT ALL ON TABLE chatmensagens TO postgres;


--
-- TOC entry 2973 (class 0 OID 0)
-- Dependencies: 191
-- Name: chatmensagens_id_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE chatmensagens_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE chatmensagens_id_seq FROM postgres;
GRANT ALL ON SEQUENCE chatmensagens_id_seq TO postgres;


--
-- TOC entry 2974 (class 0 OID 0)
-- Dependencies: 192
-- Name: chatmensagensstatus; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE chatmensagensstatus FROM PUBLIC;
REVOKE ALL ON TABLE chatmensagensstatus FROM postgres;
GRANT ALL ON TABLE chatmensagensstatus TO postgres;


--
-- TOC entry 2976 (class 0 OID 0)
-- Dependencies: 193
-- Name: chatmensagensstatus_id_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE chatmensagensstatus_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE chatmensagensstatus_id_seq FROM postgres;
GRANT ALL ON SEQUENCE chatmensagensstatus_id_seq TO postgres;


--
-- TOC entry 2977 (class 0 OID 0)
-- Dependencies: 194
-- Name: componentecurricular_idcomponentecurricular_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE componentecurricular_idcomponentecurricular_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE componentecurricular_idcomponentecurricular_seq FROM postgres;
GRANT ALL ON SEQUENCE componentecurricular_idcomponentecurricular_seq TO postgres;


--
-- TOC entry 2978 (class 0 OID 0)
-- Dependencies: 195
-- Name: componentecurricular; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE componentecurricular FROM PUBLIC;
REVOKE ALL ON TABLE componentecurricular FROM postgres;
GRANT ALL ON TABLE componentecurricular TO postgres;


--
-- TOC entry 2979 (class 0 OID 0)
-- Dependencies: 196
-- Name: componentecurriculartopico; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE componentecurriculartopico FROM PUBLIC;
REVOKE ALL ON TABLE componentecurriculartopico FROM postgres;
GRANT ALL ON TABLE componentecurriculartopico TO postgres;


--
-- TOC entry 2981 (class 0 OID 0)
-- Dependencies: 197
-- Name: componentecurriculartopico_idcomponentecurriculartopico_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE componentecurriculartopico_idcomponentecurriculartopico_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE componentecurriculartopico_idcomponentecurriculartopico_seq FROM postgres;
GRANT ALL ON SEQUENCE componentecurriculartopico_idcomponentecurriculartopico_seq TO postgres;


--
-- TOC entry 2982 (class 0 OID 0)
-- Dependencies: 198
-- Name: comuagenda_idcomunidaderelacionada_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE comuagenda_idcomunidaderelacionada_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE comuagenda_idcomunidaderelacionada_seq FROM postgres;
GRANT ALL ON SEQUENCE comuagenda_idcomunidaderelacionada_seq TO postgres;


--
-- TOC entry 2983 (class 0 OID 0)
-- Dependencies: 199
-- Name: comunidade_idcomunidade_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE comunidade_idcomunidade_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE comunidade_idcomunidade_seq FROM postgres;
GRANT ALL ON SEQUENCE comunidade_idcomunidade_seq TO postgres;


--
-- TOC entry 2984 (class 0 OID 0)
-- Dependencies: 200
-- Name: comunidade; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE comunidade FROM PUBLIC;
REVOKE ALL ON TABLE comunidade FROM postgres;
GRANT ALL ON TABLE comunidade TO postgres;


--
-- TOC entry 2985 (class 0 OID 0)
-- Dependencies: 201
-- Name: comunidadeagenda; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE comunidadeagenda FROM PUBLIC;
REVOKE ALL ON TABLE comunidadeagenda FROM postgres;
GRANT ALL ON TABLE comunidadeagenda TO postgres;


--
-- TOC entry 2987 (class 0 OID 0)
-- Dependencies: 202
-- Name: comunidadeagenda_idcomunidadeagenda_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE comunidadeagenda_idcomunidadeagenda_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE comunidadeagenda_idcomunidadeagenda_seq FROM postgres;
GRANT ALL ON SEQUENCE comunidadeagenda_idcomunidadeagenda_seq TO postgres;


--
-- TOC entry 2988 (class 0 OID 0)
-- Dependencies: 203
-- Name: comunidadealbum; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE comunidadealbum FROM PUBLIC;
REVOKE ALL ON TABLE comunidadealbum FROM postgres;
GRANT ALL ON TABLE comunidadealbum TO postgres;


--
-- TOC entry 2990 (class 0 OID 0)
-- Dependencies: 204
-- Name: comunidadealbum_idcomunidadealbum_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE comunidadealbum_idcomunidadealbum_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE comunidadealbum_idcomunidadealbum_seq FROM postgres;
GRANT ALL ON SEQUENCE comunidadealbum_idcomunidadealbum_seq TO postgres;


--
-- TOC entry 2991 (class 0 OID 0)
-- Dependencies: 205
-- Name: comunidadealbumfoto; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE comunidadealbumfoto FROM PUBLIC;
REVOKE ALL ON TABLE comunidadealbumfoto FROM postgres;
GRANT ALL ON TABLE comunidadealbumfoto TO postgres;


--
-- TOC entry 2993 (class 0 OID 0)
-- Dependencies: 206
-- Name: comunidadealbumfoto_idcomunidadealbumfoto_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE comunidadealbumfoto_idcomunidadealbumfoto_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE comunidadealbumfoto_idcomunidadealbumfoto_seq FROM postgres;
GRANT ALL ON SEQUENCE comunidadealbumfoto_idcomunidadealbumfoto_seq TO postgres;


--
-- TOC entry 2994 (class 0 OID 0)
-- Dependencies: 207
-- Name: comunidadeblog; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE comunidadeblog FROM PUBLIC;
REVOKE ALL ON TABLE comunidadeblog FROM postgres;
GRANT ALL ON TABLE comunidadeblog TO postgres;


--
-- TOC entry 2996 (class 0 OID 0)
-- Dependencies: 208
-- Name: comunidadeblog_idcomunidadeblog_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE comunidadeblog_idcomunidadeblog_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE comunidadeblog_idcomunidadeblog_seq FROM postgres;
GRANT ALL ON SEQUENCE comunidadeblog_idcomunidadeblog_seq TO postgres;


--
-- TOC entry 2997 (class 0 OID 0)
-- Dependencies: 209
-- Name: comunidadefoto; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE comunidadefoto FROM PUBLIC;
REVOKE ALL ON TABLE comunidadefoto FROM postgres;
GRANT ALL ON TABLE comunidadefoto TO postgres;


--
-- TOC entry 2999 (class 0 OID 0)
-- Dependencies: 210
-- Name: comunidadefoto_idcomunidadefoto_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE comunidadefoto_idcomunidadefoto_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE comunidadefoto_idcomunidadefoto_seq FROM postgres;
GRANT ALL ON SEQUENCE comunidadefoto_idcomunidadefoto_seq TO postgres;


--
-- TOC entry 3000 (class 0 OID 0)
-- Dependencies: 211
-- Name: comunidadesugerida; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE comunidadesugerida FROM PUBLIC;
REVOKE ALL ON TABLE comunidadesugerida FROM postgres;
GRANT ALL ON TABLE comunidadesugerida TO postgres;


--
-- TOC entry 3001 (class 0 OID 0)
-- Dependencies: 212
-- Name: comunidadetag; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE comunidadetag FROM PUBLIC;
REVOKE ALL ON TABLE comunidadetag FROM postgres;
GRANT ALL ON TABLE comunidadetag TO postgres;


--
-- TOC entry 3002 (class 0 OID 0)
-- Dependencies: 213
-- Name: comurelacionada_idcomurelacionada_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE comurelacionada_idcomurelacionada_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE comurelacionada_idcomurelacionada_seq FROM postgres;
GRANT ALL ON SEQUENCE comurelacionada_idcomurelacionada_seq TO postgres;


--
-- TOC entry 3003 (class 0 OID 0)
-- Dependencies: 214
-- Name: comurelacionada; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE comurelacionada FROM PUBLIC;
REVOKE ALL ON TABLE comurelacionada FROM postgres;
GRANT ALL ON TABLE comurelacionada TO postgres;


--
-- TOC entry 3004 (class 0 OID 0)
-- Dependencies: 215
-- Name: comutopico_idcomutopico_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE comutopico_idcomutopico_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE comutopico_idcomutopico_seq FROM postgres;
GRANT ALL ON SEQUENCE comutopico_idcomutopico_seq TO postgres;


--
-- TOC entry 3005 (class 0 OID 0)
-- Dependencies: 216
-- Name: comutopico; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE comutopico FROM PUBLIC;
REVOKE ALL ON TABLE comutopico FROM postgres;
GRANT ALL ON TABLE comutopico TO postgres;


--
-- TOC entry 3006 (class 0 OID 0)
-- Dependencies: 217
-- Name: comutopicomsg_idcomutopicomsg_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE comutopicomsg_idcomutopicomsg_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE comutopicomsg_idcomutopicomsg_seq FROM postgres;
GRANT ALL ON SEQUENCE comutopicomsg_idcomutopicomsg_seq TO postgres;


--
-- TOC entry 3007 (class 0 OID 0)
-- Dependencies: 218
-- Name: comutopicomsg; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE comutopicomsg FROM PUBLIC;
REVOKE ALL ON TABLE comutopicomsg FROM postgres;
GRANT ALL ON TABLE comutopicomsg TO postgres;


--
-- TOC entry 3008 (class 0 OID 0)
-- Dependencies: 219
-- Name: comuusuario; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE comuusuario FROM PUBLIC;
REVOKE ALL ON TABLE comuusuario FROM postgres;
GRANT ALL ON TABLE comuusuario TO postgres;


--
-- TOC entry 3010 (class 0 OID 0)
-- Dependencies: 220
-- Name: comuusuario_idcomuusuario_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE comuusuario_idcomuusuario_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE comuusuario_idcomuusuario_seq FROM postgres;
GRANT ALL ON SEQUENCE comuusuario_idcomuusuario_seq TO postgres;


--
-- TOC entry 3011 (class 0 OID 0)
-- Dependencies: 221
-- Name: comuvoto_idcomuvoto_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE comuvoto_idcomuvoto_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE comuvoto_idcomuvoto_seq FROM postgres;
GRANT ALL ON SEQUENCE comuvoto_idcomuvoto_seq TO postgres;


--
-- TOC entry 3012 (class 0 OID 0)
-- Dependencies: 222
-- Name: comuvoto; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE comuvoto FROM PUBLIC;
REVOKE ALL ON TABLE comuvoto FROM postgres;
GRANT ALL ON TABLE comuvoto TO postgres;


--
-- TOC entry 3013 (class 0 OID 0)
-- Dependencies: 223
-- Name: conteudodigital_idconteudodigital_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE conteudodigital_idconteudodigital_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE conteudodigital_idconteudodigital_seq FROM postgres;
GRANT ALL ON SEQUENCE conteudodigital_idconteudodigital_seq TO postgres;


--
-- TOC entry 3014 (class 0 OID 0)
-- Dependencies: 224
-- Name: conteudodigital; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE conteudodigital FROM PUBLIC;
REVOKE ALL ON TABLE conteudodigital FROM postgres;
GRANT ALL ON TABLE conteudodigital TO postgres;


--
-- TOC entry 3015 (class 0 OID 0)
-- Dependencies: 225
-- Name: conteudodigitalcategoria; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE conteudodigitalcategoria FROM PUBLIC;
REVOKE ALL ON TABLE conteudodigitalcategoria FROM postgres;
GRANT ALL ON TABLE conteudodigitalcategoria TO postgres;


--
-- TOC entry 3017 (class 0 OID 0)
-- Dependencies: 226
-- Name: conteudodigitalcategoria_idconteudodigitalcategoria_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE conteudodigitalcategoria_idconteudodigitalcategoria_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE conteudodigitalcategoria_idconteudodigitalcategoria_seq FROM postgres;
GRANT ALL ON SEQUENCE conteudodigitalcategoria_idconteudodigitalcategoria_seq TO postgres;


--
-- TOC entry 3018 (class 0 OID 0)
-- Dependencies: 227
-- Name: conteudodigitalcomentario_idconteudodigitalcomentario_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE conteudodigitalcomentario_idconteudodigitalcomentario_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE conteudodigitalcomentario_idconteudodigitalcomentario_seq FROM postgres;
GRANT ALL ON SEQUENCE conteudodigitalcomentario_idconteudodigitalcomentario_seq TO postgres;


--
-- TOC entry 3019 (class 0 OID 0)
-- Dependencies: 228
-- Name: conteudodigitalcomentario; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE conteudodigitalcomentario FROM PUBLIC;
REVOKE ALL ON TABLE conteudodigitalcomentario FROM postgres;
GRANT ALL ON TABLE conteudodigitalcomentario TO postgres;


--
-- TOC entry 3020 (class 0 OID 0)
-- Dependencies: 229
-- Name: conteudodigitalcomponente; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE conteudodigitalcomponente FROM PUBLIC;
REVOKE ALL ON TABLE conteudodigitalcomponente FROM postgres;
GRANT ALL ON TABLE conteudodigitalcomponente TO postgres;


--
-- TOC entry 3021 (class 0 OID 0)
-- Dependencies: 230
-- Name: conteudodigitalfavorito; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE conteudodigitalfavorito FROM PUBLIC;
REVOKE ALL ON TABLE conteudodigitalfavorito FROM postgres;
GRANT ALL ON TABLE conteudodigitalfavorito TO postgres;


--
-- TOC entry 3022 (class 0 OID 0)
-- Dependencies: 231
-- Name: conteudodigitalrelacionado; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE conteudodigitalrelacionado FROM PUBLIC;
REVOKE ALL ON TABLE conteudodigitalrelacionado FROM postgres;
GRANT ALL ON TABLE conteudodigitalrelacionado TO postgres;


--
-- TOC entry 3023 (class 0 OID 0)
-- Dependencies: 232
-- Name: conteudodigitaltag; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE conteudodigitaltag FROM PUBLIC;
REVOKE ALL ON TABLE conteudodigitaltag FROM postgres;
GRANT ALL ON TABLE conteudodigitaltag TO postgres;


--
-- TOC entry 3024 (class 0 OID 0)
-- Dependencies: 233
-- Name: conteudodigitalvoto_idconteudodigitalvoto_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE conteudodigitalvoto_idconteudodigitalvoto_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE conteudodigitalvoto_idconteudodigitalvoto_seq FROM postgres;
GRANT ALL ON SEQUENCE conteudodigitalvoto_idconteudodigitalvoto_seq TO postgres;


--
-- TOC entry 3025 (class 0 OID 0)
-- Dependencies: 234
-- Name: conteudodigitalvoto; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE conteudodigitalvoto FROM PUBLIC;
REVOKE ALL ON TABLE conteudodigitalvoto FROM postgres;
GRANT ALL ON TABLE conteudodigitalvoto TO postgres;


--
-- TOC entry 3026 (class 0 OID 0)
-- Dependencies: 235
-- Name: conteudolicenca; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE conteudolicenca FROM PUBLIC;
REVOKE ALL ON TABLE conteudolicenca FROM postgres;
GRANT ALL ON TABLE conteudolicenca TO postgres;


--
-- TOC entry 3028 (class 0 OID 0)
-- Dependencies: 236
-- Name: conteudolicenca_idconteudolicenca_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE conteudolicenca_idconteudolicenca_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE conteudolicenca_idconteudolicenca_seq FROM postgres;
GRANT ALL ON SEQUENCE conteudolicenca_idconteudolicenca_seq TO postgres;


--
-- TOC entry 3029 (class 0 OID 0)
-- Dependencies: 237
-- Name: conteudotipo_idconteudotipo_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE conteudotipo_idconteudotipo_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE conteudotipo_idconteudotipo_seq FROM postgres;
GRANT ALL ON SEQUENCE conteudotipo_idconteudotipo_seq TO postgres;


--
-- TOC entry 3030 (class 0 OID 0)
-- Dependencies: 238
-- Name: conteudotipo; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE conteudotipo FROM PUBLIC;
REVOKE ALL ON TABLE conteudotipo FROM postgres;
GRANT ALL ON TABLE conteudotipo TO postgres;


--
-- TOC entry 3031 (class 0 OID 0)
-- Dependencies: 239
-- Name: denuncia_iddenuncia_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE denuncia_iddenuncia_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE denuncia_iddenuncia_seq FROM postgres;
GRANT ALL ON SEQUENCE denuncia_iddenuncia_seq TO postgres;


--
-- TOC entry 3032 (class 0 OID 0)
-- Dependencies: 240
-- Name: denuncia; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE denuncia FROM PUBLIC;
REVOKE ALL ON TABLE denuncia FROM postgres;
GRANT ALL ON TABLE denuncia TO postgres;


--
-- TOC entry 3033 (class 0 OID 0)
-- Dependencies: 241
-- Name: dispositivo; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE dispositivo FROM PUBLIC;
REVOKE ALL ON TABLE dispositivo FROM postgres;
GRANT ALL ON TABLE dispositivo TO postgres;


--
-- TOC entry 3035 (class 0 OID 0)
-- Dependencies: 242
-- Name: dispositivo_iddispositivo_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE dispositivo_iddispositivo_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE dispositivo_iddispositivo_seq FROM postgres;
GRANT ALL ON SEQUENCE dispositivo_iddispositivo_seq TO postgres;


--
-- TOC entry 3036 (class 0 OID 0)
-- Dependencies: 243
-- Name: enquete_idenquete_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE enquete_idenquete_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE enquete_idenquete_seq FROM postgres;
GRANT ALL ON SEQUENCE enquete_idenquete_seq TO postgres;


--
-- TOC entry 3037 (class 0 OID 0)
-- Dependencies: 244
-- Name: enquete; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE enquete FROM PUBLIC;
REVOKE ALL ON TABLE enquete FROM postgres;
GRANT ALL ON TABLE enquete TO postgres;


--
-- TOC entry 3038 (class 0 OID 0)
-- Dependencies: 245
-- Name: enqueteopcao_idenqueteopcao_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE enqueteopcao_idenqueteopcao_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE enqueteopcao_idenqueteopcao_seq FROM postgres;
GRANT ALL ON SEQUENCE enqueteopcao_idenqueteopcao_seq TO postgres;


--
-- TOC entry 3039 (class 0 OID 0)
-- Dependencies: 246
-- Name: enqueteopcao; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE enqueteopcao FROM PUBLIC;
REVOKE ALL ON TABLE enqueteopcao FROM postgres;
GRANT ALL ON TABLE enqueteopcao TO postgres;


--
-- TOC entry 3040 (class 0 OID 0)
-- Dependencies: 247
-- Name: enqueteopcaoresposta_idenqueteopcaoresposta_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE enqueteopcaoresposta_idenqueteopcaoresposta_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE enqueteopcaoresposta_idenqueteopcaoresposta_seq FROM postgres;
GRANT ALL ON SEQUENCE enqueteopcaoresposta_idenqueteopcaoresposta_seq TO postgres;


--
-- TOC entry 3041 (class 0 OID 0)
-- Dependencies: 248
-- Name: enqueteopcaoresposta; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE enqueteopcaoresposta FROM PUBLIC;
REVOKE ALL ON TABLE enqueteopcaoresposta FROM postgres;
GRANT ALL ON TABLE enqueteopcaoresposta TO postgres;


--
-- TOC entry 3042 (class 0 OID 0)
-- Dependencies: 249
-- Name: escola_idescola_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE escola_idescola_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE escola_idescola_seq FROM postgres;
GRANT ALL ON SEQUENCE escola_idescola_seq TO postgres;


--
-- TOC entry 3043 (class 0 OID 0)
-- Dependencies: 250
-- Name: escola; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE escola FROM PUBLIC;
REVOKE ALL ON TABLE escola FROM postgres;
GRANT ALL ON TABLE escola TO postgres;


--
-- TOC entry 3044 (class 0 OID 0)
-- Dependencies: 251
-- Name: estado_idestado_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE estado_idestado_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE estado_idestado_seq FROM postgres;
GRANT ALL ON SEQUENCE estado_idestado_seq TO postgres;


--
-- TOC entry 3045 (class 0 OID 0)
-- Dependencies: 252
-- Name: estado; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE estado FROM PUBLIC;
REVOKE ALL ON TABLE estado FROM postgres;
GRANT ALL ON TABLE estado TO postgres;


--
-- TOC entry 3046 (class 0 OID 0)
-- Dependencies: 253
-- Name: favorito_idfavorito_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE favorito_idfavorito_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE favorito_idfavorito_seq FROM postgres;
GRANT ALL ON SEQUENCE favorito_idfavorito_seq TO postgres;


--
-- TOC entry 3047 (class 0 OID 0)
-- Dependencies: 254
-- Name: favorito; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE favorito FROM PUBLIC;
REVOKE ALL ON TABLE favorito FROM postgres;
GRANT ALL ON TABLE favorito TO postgres;


--
-- TOC entry 3048 (class 0 OID 0)
-- Dependencies: 255
-- Name: feedcontagem; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE feedcontagem FROM PUBLIC;
REVOKE ALL ON TABLE feedcontagem FROM postgres;
GRANT ALL ON TABLE feedcontagem TO postgres;


--
-- TOC entry 3050 (class 0 OID 0)
-- Dependencies: 256
-- Name: feedcontagem_idfeedcontagem_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE feedcontagem_idfeedcontagem_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE feedcontagem_idfeedcontagem_seq FROM postgres;
GRANT ALL ON SEQUENCE feedcontagem_idfeedcontagem_seq TO postgres;


--
-- TOC entry 3051 (class 0 OID 0)
-- Dependencies: 257
-- Name: feeddetalhe; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE feeddetalhe FROM PUBLIC;
REVOKE ALL ON TABLE feeddetalhe FROM postgres;
GRANT ALL ON TABLE feeddetalhe TO postgres;


--
-- TOC entry 3053 (class 0 OID 0)
-- Dependencies: 258
-- Name: feeddetalhe_id_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE feeddetalhe_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE feeddetalhe_id_seq FROM postgres;
GRANT ALL ON SEQUENCE feeddetalhe_id_seq TO postgres;


--
-- TOC entry 3054 (class 0 OID 0)
-- Dependencies: 259
-- Name: feedtabela; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE feedtabela FROM PUBLIC;
REVOKE ALL ON TABLE feedtabela FROM postgres;
GRANT ALL ON TABLE feedtabela TO postgres;


--
-- TOC entry 3056 (class 0 OID 0)
-- Dependencies: 260
-- Name: feedtabela_id_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE feedtabela_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE feedtabela_id_seq FROM postgres;
GRANT ALL ON SEQUENCE feedtabela_id_seq TO postgres;


--
-- TOC entry 3057 (class 0 OID 0)
-- Dependencies: 261
-- Name: feedtipo; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE feedtipo FROM PUBLIC;
REVOKE ALL ON TABLE feedtipo FROM postgres;
GRANT ALL ON TABLE feedtipo TO postgres;


--
-- TOC entry 3059 (class 0 OID 0)
-- Dependencies: 262
-- Name: feedtipo_id_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE feedtipo_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE feedtipo_id_seq FROM postgres;
GRANT ALL ON SEQUENCE feedtipo_id_seq TO postgres;


--
-- TOC entry 3060 (class 0 OID 0)
-- Dependencies: 263
-- Name: formato_idformato_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE formato_idformato_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE formato_idformato_seq FROM postgres;
GRANT ALL ON SEQUENCE formato_idformato_seq TO postgres;


--
-- TOC entry 3061 (class 0 OID 0)
-- Dependencies: 264
-- Name: formato; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE formato FROM PUBLIC;
REVOKE ALL ON TABLE formato FROM postgres;
GRANT ALL ON TABLE formato TO postgres;


--
-- TOC entry 3062 (class 0 OID 0)
-- Dependencies: 265
-- Name: marcacaoagenda; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE marcacaoagenda FROM PUBLIC;
REVOKE ALL ON TABLE marcacaoagenda FROM postgres;
GRANT ALL ON TABLE marcacaoagenda TO postgres;


--
-- TOC entry 3063 (class 0 OID 0)
-- Dependencies: 266
-- Name: municipio_idmunicipio_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE municipio_idmunicipio_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE municipio_idmunicipio_seq FROM postgres;
GRANT ALL ON SEQUENCE municipio_idmunicipio_seq TO postgres;


--
-- TOC entry 3064 (class 0 OID 0)
-- Dependencies: 267
-- Name: municipio; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE municipio FROM PUBLIC;
REVOKE ALL ON TABLE municipio FROM postgres;
GRANT ALL ON TABLE municipio TO postgres;


--
-- TOC entry 3065 (class 0 OID 0)
-- Dependencies: 268
-- Name: nivelensino_idnivelensino_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE nivelensino_idnivelensino_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE nivelensino_idnivelensino_seq FROM postgres;
GRANT ALL ON SEQUENCE nivelensino_idnivelensino_seq TO postgres;


--
-- TOC entry 3066 (class 0 OID 0)
-- Dependencies: 269
-- Name: nivelensino; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE nivelensino FROM PUBLIC;
REVOKE ALL ON TABLE nivelensino FROM postgres;
GRANT ALL ON TABLE nivelensino TO postgres;


--
-- TOC entry 3067 (class 0 OID 0)
-- Dependencies: 270
-- Name: numerocomponentes; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE numerocomponentes FROM PUBLIC;
REVOKE ALL ON TABLE numerocomponentes FROM postgres;
GRANT ALL ON TABLE numerocomponentes TO postgres;


--
-- TOC entry 3068 (class 0 OID 0)
-- Dependencies: 271
-- Name: redesocial; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE redesocial FROM PUBLIC;
REVOKE ALL ON TABLE redesocial FROM postgres;
GRANT ALL ON TABLE redesocial TO postgres;


--
-- TOC entry 3070 (class 0 OID 0)
-- Dependencies: 272
-- Name: redesocial_idredesocial_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE redesocial_idredesocial_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE redesocial_idredesocial_seq FROM postgres;
GRANT ALL ON SEQUENCE redesocial_idredesocial_seq TO postgres;


--
-- TOC entry 3071 (class 0 OID 0)
-- Dependencies: 273
-- Name: serie_idserie_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE serie_idserie_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE serie_idserie_seq FROM postgres;
GRANT ALL ON SEQUENCE serie_idserie_seq TO postgres;


--
-- TOC entry 3072 (class 0 OID 0)
-- Dependencies: 274
-- Name: serie; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE serie FROM PUBLIC;
REVOKE ALL ON TABLE serie FROM postgres;
GRANT ALL ON TABLE serie TO postgres;


--
-- TOC entry 3073 (class 0 OID 0)
-- Dependencies: 275
-- Name: servidor; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE servidor FROM PUBLIC;
REVOKE ALL ON TABLE servidor FROM postgres;
GRANT ALL ON TABLE servidor TO postgres;


--
-- TOC entry 3075 (class 0 OID 0)
-- Dependencies: 276
-- Name: servidor_idservidor_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE servidor_idservidor_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE servidor_idservidor_seq FROM postgres;
GRANT ALL ON SEQUENCE servidor_idservidor_seq TO postgres;


--
-- TOC entry 3076 (class 0 OID 0)
-- Dependencies: 277
-- Name: tag_idtag_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE tag_idtag_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE tag_idtag_seq FROM postgres;
GRANT ALL ON SEQUENCE tag_idtag_seq TO postgres;


--
-- TOC entry 3077 (class 0 OID 0)
-- Dependencies: 278
-- Name: tag; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE tag FROM PUBLIC;
REVOKE ALL ON TABLE tag FROM postgres;
GRANT ALL ON TABLE tag TO postgres;


--
-- TOC entry 3078 (class 0 OID 0)
-- Dependencies: 279
-- Name: usuario_idusuario_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE usuario_idusuario_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE usuario_idusuario_seq FROM postgres;
GRANT ALL ON SEQUENCE usuario_idusuario_seq TO postgres;


--
-- TOC entry 3079 (class 0 OID 0)
-- Dependencies: 280
-- Name: usuario; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE usuario FROM PUBLIC;
REVOKE ALL ON TABLE usuario FROM postgres;
GRANT ALL ON TABLE usuario TO postgres;


--
-- TOC entry 3080 (class 0 OID 0)
-- Dependencies: 281
-- Name: usuarioagenda_idusuarioagenda_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE usuarioagenda_idusuarioagenda_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE usuarioagenda_idusuarioagenda_seq FROM postgres;
GRANT ALL ON SEQUENCE usuarioagenda_idusuarioagenda_seq TO postgres;


--
-- TOC entry 3081 (class 0 OID 0)
-- Dependencies: 282
-- Name: usuarioagenda; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE usuarioagenda FROM PUBLIC;
REVOKE ALL ON TABLE usuarioagenda FROM postgres;
GRANT ALL ON TABLE usuarioagenda TO postgres;


--
-- TOC entry 3082 (class 0 OID 0)
-- Dependencies: 283
-- Name: usuarioalbum; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE usuarioalbum FROM PUBLIC;
REVOKE ALL ON TABLE usuarioalbum FROM postgres;
GRANT ALL ON TABLE usuarioalbum TO postgres;


--
-- TOC entry 3084 (class 0 OID 0)
-- Dependencies: 284
-- Name: usuarioalbum_idusuarioalbum_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE usuarioalbum_idusuarioalbum_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE usuarioalbum_idusuarioalbum_seq FROM postgres;
GRANT ALL ON SEQUENCE usuarioalbum_idusuarioalbum_seq TO postgres;


--
-- TOC entry 3085 (class 0 OID 0)
-- Dependencies: 285
-- Name: usuarioalbumfoto; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE usuarioalbumfoto FROM PUBLIC;
REVOKE ALL ON TABLE usuarioalbumfoto FROM postgres;
GRANT ALL ON TABLE usuarioalbumfoto TO postgres;


--
-- TOC entry 3087 (class 0 OID 0)
-- Dependencies: 286
-- Name: usuarioalbumfoto_idusuarioalbumfoto_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE usuarioalbumfoto_idusuarioalbumfoto_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE usuarioalbumfoto_idusuarioalbumfoto_seq FROM postgres;
GRANT ALL ON SEQUENCE usuarioalbumfoto_idusuarioalbumfoto_seq TO postgres;


--
-- TOC entry 3088 (class 0 OID 0)
-- Dependencies: 287
-- Name: usuarioamigo_idusuarioamigo_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE usuarioamigo_idusuarioamigo_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE usuarioamigo_idusuarioamigo_seq FROM postgres;
GRANT ALL ON SEQUENCE usuarioamigo_idusuarioamigo_seq TO postgres;


--
-- TOC entry 3089 (class 0 OID 0)
-- Dependencies: 288
-- Name: usuarioamigo; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE usuarioamigo FROM PUBLIC;
REVOKE ALL ON TABLE usuarioamigo FROM postgres;
GRANT ALL ON TABLE usuarioamigo TO postgres;


--
-- TOC entry 3090 (class 0 OID 0)
-- Dependencies: 289
-- Name: usuariocolega; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE usuariocolega FROM PUBLIC;
REVOKE ALL ON TABLE usuariocolega FROM postgres;
GRANT ALL ON TABLE usuariocolega TO postgres;


--
-- TOC entry 3092 (class 0 OID 0)
-- Dependencies: 290
-- Name: usuariocolega_idusuariocolega_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE usuariocolega_idusuariocolega_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE usuariocolega_idusuariocolega_seq FROM postgres;
GRANT ALL ON SEQUENCE usuariocolega_idusuariocolega_seq TO postgres;


--
-- TOC entry 3093 (class 0 OID 0)
-- Dependencies: 291
-- Name: usuariocomponente; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE usuariocomponente FROM PUBLIC;
REVOKE ALL ON TABLE usuariocomponente FROM postgres;
GRANT ALL ON TABLE usuariocomponente TO postgres;


--
-- TOC entry 3094 (class 0 OID 0)
-- Dependencies: 292
-- Name: usuariofoto; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE usuariofoto FROM PUBLIC;
REVOKE ALL ON TABLE usuariofoto FROM postgres;
GRANT ALL ON TABLE usuariofoto TO postgres;


--
-- TOC entry 3096 (class 0 OID 0)
-- Dependencies: 293
-- Name: usuariofoto_idusuariofoto_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE usuariofoto_idusuariofoto_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE usuariofoto_idusuariofoto_seq FROM postgres;
GRANT ALL ON SEQUENCE usuariofoto_idusuariofoto_seq TO postgres;


--
-- TOC entry 3097 (class 0 OID 0)
-- Dependencies: 294
-- Name: usuariorecado_idusuariorecado_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE usuariorecado_idusuariorecado_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE usuariorecado_idusuariorecado_seq FROM postgres;
GRANT ALL ON SEQUENCE usuariorecado_idusuariorecado_seq TO postgres;


--
-- TOC entry 3098 (class 0 OID 0)
-- Dependencies: 295
-- Name: usuariorecado; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE usuariorecado FROM PUBLIC;
REVOKE ALL ON TABLE usuariorecado FROM postgres;
GRANT ALL ON TABLE usuariorecado TO postgres;


--
-- TOC entry 3099 (class 0 OID 0)
-- Dependencies: 296
-- Name: usuarioredesocial; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE usuarioredesocial FROM PUBLIC;
REVOKE ALL ON TABLE usuarioredesocial FROM postgres;
GRANT ALL ON TABLE usuarioredesocial TO postgres;


--
-- TOC entry 3101 (class 0 OID 0)
-- Dependencies: 297
-- Name: usuarioredesocial_idusuarioredesocial_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE usuarioredesocial_idusuarioredesocial_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE usuarioredesocial_idusuarioredesocial_seq FROM postgres;
GRANT ALL ON SEQUENCE usuarioredesocial_idusuarioredesocial_seq TO postgres;


--
-- TOC entry 3102 (class 0 OID 0)
-- Dependencies: 298
-- Name: usuariosobremimperfil; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE usuariosobremimperfil FROM PUBLIC;
REVOKE ALL ON TABLE usuariosobremimperfil FROM postgres;
GRANT ALL ON TABLE usuariosobremimperfil TO postgres;


--
-- TOC entry 3103 (class 0 OID 0)
-- Dependencies: 299
-- Name: usuariotag; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE usuariotag FROM PUBLIC;
REVOKE ALL ON TABLE usuariotag FROM postgres;
GRANT ALL ON TABLE usuariotag TO postgres;


--
-- TOC entry 3104 (class 0 OID 0)
-- Dependencies: 300
-- Name: usuariotipo_idusuariotipo_seq; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON SEQUENCE usuariotipo_idusuariotipo_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE usuariotipo_idusuariotipo_seq FROM postgres;
GRANT ALL ON SEQUENCE usuariotipo_idusuariotipo_seq TO postgres;


--
-- TOC entry 3105 (class 0 OID 0)
-- Dependencies: 301
-- Name: usuariotipo; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE usuariotipo FROM PUBLIC;
REVOKE ALL ON TABLE usuariotipo FROM postgres;
GRANT ALL ON TABLE usuariotipo TO postgres;


--
-- TOC entry 3106 (class 0 OID 0)
-- Dependencies: 302
-- Name: vw_conteudos; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE vw_conteudos FROM PUBLIC;
REVOKE ALL ON TABLE vw_conteudos FROM postgres;
GRANT ALL ON TABLE vw_conteudos TO postgres;


--
-- TOC entry 3107 (class 0 OID 0)
-- Dependencies: 303
-- Name: vw_sitestematicos; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TABLE vw_sitestematicos FROM PUBLIC;
REVOKE ALL ON TABLE vw_sitestematicos FROM postgres;
GRANT ALL ON TABLE vw_sitestematicos TO postgres;


-- Completed on 2016-06-06 10:48:59 BRT

--
-- PostgreSQL database dump complete
--

