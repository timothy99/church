create table gwt_session (
    id varchar(128) collate utf8_unicode_ci not null,
    ip_address varchar(45) collate utf8_unicode_ci not null,
    timestamp int(10) unsigned not null default 0,
    data mediumblob not null,
    key ci_sessions_timestamp (timestamp)
) engine=InnoDB default charset=utf8 collate=utf8_unicode_ci comment='CodeIgniter를 위한 db session용 테이블';

create table gwt_meal (
    m_idx int not null auto_increment comment '연번',
    meal_date varchar(10) not null comment '식단 날짜',
    meal_menu varchar(500) not null comment '메뉴 목록',
    del_yn enum('Y', 'N') not null comment '삭제 여부',
    ins_id varchar(200) not null comment '등록자',
    ins_date varchar(14) not null comment '등록일',
    upd_id varchar(200) not null comment '수정자',
    upd_date varchar(14) not null comment '수정일',
    primary key (m_idx),
    key meal_date (meal_date,del_yn)
) engine=InnoDB auto_increment=1 default charset=utf8 comment='구내식당 식단';

create table gwt_user (
    user_idx int not null auto_increment comment '연번',
    user_id varchar(200) not null comment '사용자 아이디',
    user_name varchar(1000) default null comment '사용자 이름(암호화)',
    user_password varchar(1000) default null comment '사용자 암호(암호화)',
    admin_yn enum('Y', 'N') default 'N' comment '관리자 여부',
    use_yn enum('Y', 'N') not null comment '사용 여부',
    del_yn enum('Y', 'N') not null comment '삭제 여부',
    ins_id varchar(200) not null comment '등록자',
    ins_date varchar(14) not null comment '등록일',
    upd_id varchar(200) not null comment '수정자',
    upd_date varchar(14) not null comment '수정일',
    primary key (user_idx),
    unique key user_id (user_id)
) engine=InnoDB auto_increment=1 default charset=utf8 comment='사용자 정보';

create table gwt_file (
    file_idx int not null auto_increment comment '연번',
    file_name_org varchar(1000) not null comment '원본 파일명',
    file_name_stored varchar(1000) not null comment '저장된 파일명',
    file_name_uploaded varchar(1000) not null comment '저장된 파일 전체 경로',
    file_size int not null comment '파일 크기',
    mime_type varchar(100) not null comment '파일 mime type',
    category varchar(100) not null comment '사용자가 지정한 파일 형식',
    del_yn enum('Y', 'N') not null comment '삭제 여부',
    ins_id varchar(200) not null comment '등록자',
    ins_date varchar(14) not null comment '등록일',
    upd_id varchar(200) not null comment '수정자',
    upd_date varchar(14) not null comment '수정일',
    primary key (file_idx),
    key file_name_org (file_name_org)
) engine=InnoDB auto_increment=1 default charset=utf8 comment='사용자 정보';

create table gwt_board (
    board_idx int not null auto_increment comment '게시물 번호',
    upper_board_idx int not null default 0 comment '답변인 경우 원래 게시물 번호',
    title varchar(1000) not null comment '제목',
    contents text not null comment '내용',
    reply_cnt int not null default 0 comment '답변 등록수',
    comment_cnt int not null default 0 comment '댓글 등록수',
    heart_cnt int not null default 0 comment '공감수',
    hit_cnt int not null default 0 comment '조회수',
    http_link varchar(1000) not null comment '링크',
    notice_yn enum('Y', 'N') not null default 'N' comment '공지사항 여부',
    del_yn enum('Y', 'N') not null default 'N' comment '삭제 여부',
    ins_id varchar(200) not null comment '등록자',
    ins_date varchar(14) not null comment '등록일',
    upd_id varchar(200) not null comment '수정자',
    upd_date varchar(14) not null comment '수정일',
    primary key (board_idx)
) engine=InnoDB auto_increment=1 default charset=utf8 comment='게시판 글';

create table gwt_board_comment (
    comment_idx int not null auto_increment comment '댓글 번호',
    board_idx int not null default 0 comment '게시물 번호',
    upper_comment_idx int not null comment '대댓글인 경우 원래 댓글 번호',
    comments text not null comment '댓글 내용',
    heart_cnt int not null default 0 comment '공감수',
    del_yn enum('Y', 'N') not null default 'N' comment '삭제 여부',
    ins_id varchar(200) not null comment '등록자',
    ins_date varchar(14) not null comment '등록일',
    upd_id varchar(200) not null comment '수정자',
    upd_date varchar(14) not null comment '수정일',
    primary key (comment_idx)
) engine=InnoDB auto_increment=1 default charset=utf8 comment='댓글';