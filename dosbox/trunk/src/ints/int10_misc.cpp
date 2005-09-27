/*
 *  Copyright (C) 2002-2005  The DOSBox Team
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 */

#include "dosbox.h"
#include "mem.h"
#include "inout.h"
#include "int10.h"



#pragma pack(1)
struct Dynamic_Functionality {
	RealPt static_table;		/* 00h   DWORD  address of static functionality table */
	Bit8u cur_mode;				/* 04h   BYTE   video mode in effect */
	Bit16u num_cols;			/* 05h   WORD   number of columns */
	Bit16u regen_size;			/* 07h   WORD   length of regen buffer in bytes */
	Bit16u regen_start;			/* 09h   WORD   starting address of regen buffer*/
	Bit16u cursor_pos[8];		/* 0Bh   WORD   cursor position for page 0-7 */
	Bit16u cursor_type;			/* 1Bh   WORD   cursor type */
	Bit8u active_page;			/* 1Dh   BYTE   active display page */
	Bit16u crtc_address;		/* 1Eh   WORD   CRTC port address */
	Bit8u reg_3x8;				/* 20h   BYTE   current setting of register (3?8) */
	Bit8u reg_3x9;				/* 21h   BYTE   current setting of register (3?9) */
	Bit8u num_rows;				/* 22h   BYTE   number of rows */
	Bit16u bytes_per_char;		/* 23h   WORD   bytes/character */
	Bit8u dcc;					/* 25h   BYTE   display combination code of active display */
	Bit8u dcc_alternate;		/* 26h   BYTE   DCC of alternate display */
	Bit16u num_colors;			/* 27h   WORD   number of colors supported in current mode */
	Bit8u num_pages;			/* 29h   BYTE   number of pages supported in current mode */
	Bit8u num_scanlines;		/* 2Ah   BYTE   number of scan lines active mode (0,1,2,3) = (200,350,400,480) */
	Bit8u pri_char_block;		/* 2Bh   BYTE   primary character block */
	Bit8u sec_char_block;		/* 2Ch   BYTE   secondary character block */
	Bit8u misc_flags;			/* 2Dh   BYTE   miscellaneous flags
									bit 0 all modes on all displays on
									1 grey summing on
									2 monochrome display attached
									3 default palette loading disabled
									4 cursor emulation enabled
									5 0 = intensity; 1 = blinking
									6 PS/2 P70 plasma display (without 9-dot wide font) active
									7 reserved
								*/
	Bit8u reserved1[3];			/* 2Eh  3 BYTEs reserved (00h) */
	Bit8u vid_mem;				/* 31h   BYTE   video memory available 00h = 64K, 01h = 128K, 02h = 192K, 03h = 256K */
	Bit8u savep_state_flag;		/* 32h   BYTE   save pointer state flags 
									bit 0 512 character set active
									1 dynamic save area present
									2 alpha font override active
									3 graphics font override active
									4 palette override active
									5 DCC override active
									6 reserved
									7 reserved
								*/
	Bit8u reserved2[13];		/*  33h 13 BYTEs reserved (00h) */
} GCC_ATTRIBUTE(packed);
#pragma pack()

void INT10_GetFuncStateInformation(PhysPt save) {
	/* set static state pointer */
	mem_writed(save,int10.rom.static_state);
	/* Copy BIOS Segment areas */
	Bit16u i;

	/* First area in Bios Seg */
	for (i=0;i<0x1e;i++) {
		mem_writeb(save+0x4+i,real_readb(BIOSMEM_SEG,BIOSMEM_CURRENT_MODE+i));
	}
	/* Second area */
	for (i=0;i<3;i++) {
		mem_writeb(save+0x22+i,real_readb(BIOSMEM_SEG,BIOSMEM_NB_ROWS+i));
	}
	/* Zero out rest of block */
	for (i=0x25;i<0x40;i++) mem_writeb(save+i,0);
	/* DCC Index */
	mem_writeb(save+0x25,real_readb(BIOSMEM_SEG,BIOSMEM_DCC_INDEX));
	Bit16u col_count=0;
	switch (CurMode->type) {
	case M_TEXT:
		if (CurMode->mode==0x7) col_count=1; else col_count=16;break; 
	case M_CGA2:
		col_count=2;break;
	case M_CGA4:
		col_count=4;break;
	case M_EGA16:
		if (CurMode->mode==0x11 || CurMode->mode==0x0f) col_count=2; else col_count=16;break; 
	case M_VGA:
		col_count=256;break;
	default:
		LOG(LOG_INT10,LOG_ERROR)("Get Func State illegal mode type %d",CurMode->type);
	}
	/* Colour count */
	mem_writew(save+0x27,col_count);
	/* Page count */
	mem_writeb(save+0x29,CurMode->ptotal);
	/* scan lines */
	switch (CurMode->sheight) {
	case 200:
		mem_writeb(save+0x2a,0);break;
	case 350:
		mem_writeb(save+0x2a,1);break;
	case 400:
		mem_writeb(save+0x2a,2);break;
	case 480:
		mem_writeb(save+0x2a,3);break;
	};
	/* misc flags */
	if (CurMode->type==M_TEXT) mem_writeb(save+0x2d,0x21);
	else mem_writeb(save+0x2d,0x01);
	/* Video Memory available */
	mem_writeb(save+0x31,3);
}

static void EGA_RIL(Bit16u dx, Bitu& port, Bitu& regs) {
	port = 0;
	regs = 0; //if nul is returned it's a single register port
	switch(dx) {
	case 0x00: /* CRT Controller (25 reg) 3B4h mono modes, 3D4h color modes */
		port = real_readw(BIOSMEM_SEG,BIOSMEM_CRTC_ADDRESS);
		regs = 25;
		break;
	case 0x08: /* Sequencer (5 registers) 3C4h */
		port = 0x3C4;
		regs = 5;
		break;
	case 0x10: /* Graphics Controller (9 registers) 3CEh */
		port = 0x3CE;
		regs = 9;
		break;
	case 0x18: /* Attribute Controller (20 registers) 3C0h */
		port = 0x3c0;
		regs = 20;
		break;
	case 0x20: /* Miscellaneous Output register 3C2h */
		port = 0x3C2;
		break;
	case 0x28: /* Feature Control register (3BAh mono modes, 3DAh color modes) */
		port = real_readw(BIOSMEM_SEG,BIOSMEM_CRTC_ADDRESS) + 6;
		break;
	case 0x30: /* Graphics 1 Position register 3CCh */
		port = 0x3CC;
		break;
	case 0x38: /* Graphics 2 Position register 3CAh */
		port = 0x3CA;
		break;
	default:
		LOG(LOG_INT10,LOG_ERROR)("unknown RIL port selection %X",dx);
		break;
	}
}

void INT10_EGA_RIL_F1(Bit8u & bl, Bit8u bh, Bit16u dx) {
	Bitu port = 0;
	Bitu regs = 0;
	EGA_RIL(dx,port,regs);
	if(regs == 0) {
		IO_Write(port,bl);
	} else {
		IO_Write(port,bl);
		IO_Write(port+1,bh);
		bl = bh;//Not sure
		LOG(LOG_INT10,LOG_NORMAL)("EGA RIL used with multi-reg");
	}
}
