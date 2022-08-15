create table gwt_session (
    id varchar(128) collate utf8_unicode_ci not null,
    ip_address varchar(45) collate utf8_unicode_ci not null,
    timestamp int(10) unsigned not null default 0,
    data blob not null,
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
    ins_date datetime not null comment '등록일',
    upd_id varchar(200) not null comment '수정자',
    upd_date datetime not null comment '수정일',
    primary key (user_idx),
    unique key user_id (user_id)
) engine=InnoDB auto_increment=1 default charset=utf8 comment='사용자 정보';
