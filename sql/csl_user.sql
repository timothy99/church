create table csl_user (
    user_idx int not null auto_increment comment '연번',
    user_id varchar(100) not null comment '사용자 아이디(암호화)',
    user_name varchar(100) default null comment '사용자 이름(암호화)',
    user_password varchar(1000) default null comment '사용자 암호(암호화)',
    use_yn enum('Y', 'N') not null comment '사용 여부',
    del_yn enum('Y', 'N') not null comment '삭제 여부',
    ins_id varchar(100) not null comment '등록자',
    ins_date datetime not null comment '등록일',
    upd_id varchar(100) not null comment '수정자',
    upd_date datetime not null comment '수정일',
    primary key (user_idx),
    unique key user_id (user_id)
) engine=InnoDB auto_increment=1 default charset=utf8 comment='사용자 정보';